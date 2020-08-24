<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SuccessPaymentCallbackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public $tries = 10;

    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var Carbon
     */
    private $payment_time;

    /**
     * @param  Payment  $payment
     * @param  Carbon|null  $now
     */
    public function __construct(Payment $payment, Carbon $now = null)
    {
        if ($now == null) {
            $now = now();
        }
        $this->payment = $payment;
        $this->payment_time = $now;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        if ($this->payment->callback_url === null) {
            return;
        }

        $data = [
            'paymentId' => $this->payment->id,
            'paymentTime' => $this->payment_time
        ];
        $response = Http::post($this->payment->callback_url, $data);

        if ($response->successful()) {
            $this->payment->update(['callback_at' => now()]);
            return;
        }

        $this->release(15);
    }
}
