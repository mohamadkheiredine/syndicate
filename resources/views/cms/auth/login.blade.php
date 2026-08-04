@extends('cms.layouts.main', ['class' => 'bg-default'])

@section('content')
@include('cms.layouts.headers.guest')

<div class="container mt--8 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card bg-secondary shadow border-0">
                <div class="card-body px-lg-5 py-lg-5">
                    <h3 class="text-center text-muted mb-3">Login with your credentials</h3>
                    <form method="POST" action="{{ route('admin.login') }}" autocomplete="off">
                        @csrf

                        {{-- Email --}}
                        <div class="form-group mb-3">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                                </div>
                                <input name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email" value="{{ old('email') }}" autofocus>
                            </div>

                            @if($errors->has('email'))
                            <span class="invalid-feedback text-danger d-block" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>

                        {{-- Password --}}
                        <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input name="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password">
                            </div>

                            @if($errors->has('password'))
                            <span class="invalid-feedback text-danger d-block" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="text-center">
                            @if(Session::has('error'))
                            <span class="invalid-feedback text-danger d-block" role="alert">
                                <strong>{{ Session::get('error') }}</strong>
                            </span>
                            @endif

                            <button type="submit" class="btn btn-primary mt-4">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
