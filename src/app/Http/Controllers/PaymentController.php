<?php

namespace App\Http\Controllers;

use App\Jobs\SuccessPaymentCallbackJob;
use App\Models\Payment;
use App\Rules\LuhnRule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class PaymentController extends Controller
{
    /**
     * Возвращает форму оплаты платежа
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function show(Request $request): Response
    {
        $sessionId = $request->get('sessionId');
        $payment = Payment::find($sessionId);

        if ($payment === null) {
            $msg = 'Ошибка. Счет не найден.';
            return response()->view('payment.error', compact('msg'), 404);
        }

        if ($payment->paid) {
            $msg = 'Счет уже оплачен.';
            return response()->view('payment.error', compact('msg'), 200);
        }

        if ($payment->isExpired()) {
            $msg = 'Ошибка. Время сессии оплаты истекло.';
            return response()->view('payment.error', compact('msg'), 410);
        }

        return response()->view('payment.form', compact('payment'), 200);
    }

    /**
     * Принимает и обрабатывает запрос из формы
     *
     * @param  Request  $request
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function submit(Request $request)
    {
        // По условию: "Валидные номера должны имитировать успешную оплату,
        //              невалидные — возвращать ошибку."
        $request->validate([
            'card_number' => ['required', 'integer', new LuhnRule()],
            'sessionId' => ['required', 'exists:payments,id']
        ]);
        $payment = Payment::findOrFail($request->get('sessionId'));

        if ($payment->paid || $payment->isExpired()) {
            return back()->withInput();
        }

        if (!$payment->update(['paid' => now()])) {
            return back()->withInput()->withErrors(['error' => 'Произошла ошибка сохранения']);
        }

        SuccessPaymentCallbackJob::dispatchIf($payment->callback_url !== null, $payment, now());

        return redirect(route('home'))->with(['success' => 'Счет успешно оплачен']);
    }
}
