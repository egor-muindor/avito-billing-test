<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ApiGetPaymentPeriodRequest;
use App\Http\Requests\ApiRegisterPaymentRequest;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class PaymentController extends ApiController
{
    /**
     * Регистрирует новую заявку на оплату.
     *
     * @param  ApiRegisterPaymentRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(ApiRegisterPaymentRequest $request): \Illuminate\Http\JsonResponse
    {
        $payment = new Payment($request->all(['target', 'amount', 'callback_url']));
        $payment->save();

        $data = [
            'sessionUrl' => route('payments.card.show')."?sessionId={$payment->id}"
        ];
        return response()->json($data, 201);
    }

    /**
     * Возвращает платежи за период.
     *
     * @param  ApiGetPaymentPeriodRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentPeriod(ApiGetPaymentPeriodRequest $request): \Illuminate\Http\JsonResponse
    {
        ['from' => $from, 'to' => $to] = $request->all(['from', 'to']);

        $from = Carbon::parse($from);
        $to = Carbon::parse($to);

        $payments = Payment::from($from)->to($to)->get();

        return response()->json($payments->toArray());
    }
}
