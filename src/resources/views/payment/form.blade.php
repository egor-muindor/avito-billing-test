@extends('layouts.app')
@php /* @var \App\Models\Payment $payment */ @endphp

@section('content')
    <div class="container">
        @include('layouts.message_block')
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col text-left">
                                <h4>Оплата платежа</h4>
                            </div>
                            <div class="col text-right">
                                <strong>Осталось времени:&nbsp;<a class="text-info" id="expire-timer"></a></strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <span>Цель платежа: <strong>{{ $payment->target }}</strong></span>
                            <br>
                            <span>Сумма платежа: <strong>{{ $payment->amount }}</strong></span>
                        </div>
                        <hr>
                        <form method="POST" action="{{ route('payments.card.submit') }}">
                            @csrf
                            <div class="form-group">
                                <label for="card_number" class="text-md-right">Номер карты</label>
                                <input id="card_number" type="text"
                                       class="form-control col-md-8 @error('card_number') is-invalid @enderror"
                                       name="card_number"
                                       value="{{ old('card_number') }}" required autocomplete="card_number" autofocus>

                                @error('card_number')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <input type="hidden" value="{{ $payment->id }}" name="sessionId">
                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let timerElement = document.getElementById('expire-timer');
        let expired = new Date('{{$payment->expires_at->toIso8601ZuluString('microseconds')}}');
        let now = new Date();
        const diffMs = (expired - now)
        let minutes = Math.floor(((diffMs % 86400000) % 3600000) / 60000);
        let seconds = Math.floor(((diffMs % 86400000) % 3600000) % 60000 / 1000);
        const updateTimer = () => {
            timerElement.innerHTML = `${minutes < 10 ? `0${minutes}` : minutes}:${seconds < 10 ? `0${seconds}` : seconds}`
        }
        updateTimer();

        const timeExpireTimer = () => {
            clearInterval(timer);
            timerElement.innerHTML = 'Время вышло'
            document.location.reload(true);
        }

        let timer = setInterval(() => {
            if (seconds <= 0 && minutes > 0) {
                seconds = 59
                minutes--;
                updateTimer();
            } else if (seconds <= 0 && minutes <= 0) {
                timeExpireTimer();
            } else {
                seconds--;
                updateTimer();
            }
        }, 1000)
        if (diffMs <= 0) {
            timeExpireTimer()
        }
    </script>
@endsection
