@extends('layouts.app')

@section('content')
    <div class="content">
        @if(session('success'))
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                        {{ session()->get('success') }}
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="jumbotron">
                    <h1 class="display-4">Avito billing test</h1>
                    <p class="lead">Задание выполнил Егор Фадеев.</p>
                    <p class="lead"><a href="/redoc">API documentation powered by ReDoc</a></p>
                </div>
            </div>
        </div>
    </div>

@endsection
