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
@php    /** @var \Illuminate\Support\ViewErrorBag $errors */ @endphp
@if($errors->has('error'))
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
@endif
