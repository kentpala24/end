@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-person-circle"></i> BIENVENIDO AL PORTAL ENDGO DE ENDPERU.
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row mb-4 justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="has-float-label">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder=" ">
                                        <label for="email">{{ __('Email Address') }}</label>
                                        <i class="bi bi-person-circle form-control-icon"></i>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="has-float-label">
                                        <i class="bi bi-lock form-control-icon"></i>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="password" placeholder=" ">
                                        <label for="password">{{ __('Password') }}</label>
                                        <i id="icon-eye" class="bi bi-eye-slash form-icon-passwd btn-show-passwd" data-passwd="password"></i>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary float-end">
                                    {{ __('Login') }} <i class="bi bi-box-arrow-in-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row mb-0">
                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
