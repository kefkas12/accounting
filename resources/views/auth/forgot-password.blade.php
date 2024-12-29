@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-header bg-transparent pb-3">
                        <div class="text-muted text-center mt-2"><b>{{ __('Reset your password') }}</b></div>
                        <div class="text-muted text-center mt-2"><small>
                        @if (session('status'))
                        {{ session('status') }}
                        @else
                        {{ __('Enter your email and please wait a few seconds') }}
                        @endif
                        </small></div>
                    </div>
                    @if (!session('status'))
                    <div class="card-body px-lg-5 py-lg-4">
                        <form role="form" method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} mb-3">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">{{ __('Send Reset Link') }}</button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <a href="{{ url('login') }}" class="text-light"><small>Already have account?</small></a>
                    </div>
                    <div class="col-6 text-right">
                        <a href="{{ url('register') }}" class="text-light"><small>Create new account</small></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
