<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/fontawesome.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #202223;
            color: #fff;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
            font-variant: small-caps
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body style="background: url({{asset('images/defaults/home_bg.gif')}}) no-repeat; background-size: cover">
<div class="flex-center position-ref full-height" style="background-color: rgba(0,0,0,0.9)">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @endauth
        </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="offset-lg-4 col-lg-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card-header text-center text-black">
                        <a href="/">
                            <div class="logo-container mb-2">
                                <img src="{{asset('images/defaults/logo.png')}}" class="img-fluid" width="200" alt="logo">
                            </div>
                        </a>
                        <h4>{{ __('Login') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="input-group no-border input-lg mb-3">
                            <div class="input-group-prepend">
                                        <span class="input-group-text text-dark">
                                            <i class="fa fa-envelope"></i></span>
                            </div>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror" name="email"
                                   value="{{ old('email') }}" required autocomplete="email" autofocus
                                   placeholder="Email...">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="input-group no-border input-lg">
                            <div class="input-group-prepend">
                                        <span class="input-group-text text-dark">
                                            <i class="fa fa-lock"></i></span>
                            </div>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password"
                                   required autocomplete="current-password" placeholder="Password...">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-dark btn-round btn-lg btn-block">
                            {{ __('Login') }}
                        </button>
                    </div>
                    <div class="pull-left">
                        @if (Route::has('password.request'))
                            <a class="link footer-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</body>
</html>

{{--@extends('layouts.app')--}}

{{--@section('content')--}}
{{--<div class="login-page">--}}
{{--    <div class="page-header header-filter">--}}
{{--        <div class="page-header-image page-header-image-custom"--}}
{{--            style="background-image:url({{asset('/images/Background.png')}})">--}}
{{--        </div>--}}
{{--        <div class="content">--}}
{{--            <div class="container">--}}
{{--                <div class="col-md-5 ml-auto mr-auto">--}}
{{--                    <div class="card card-login card-plain">--}}
{{--                        <form method="POST" action="{{ route('login') }}">--}}
{{--                            @csrf--}}
{{--                            <div class="card-header text-center text-black">--}}
{{--                                <div class="logo-container mb-2">--}}
{{--                                     <img src="{{asset('images/defaults/logo.png')}}" class="img-fluid" width="200" alt="logo">--}}
{{--                                </div>--}}
{{--                                <h4>{{ __('Login') }}</h4>--}}
{{--                            </div>--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="input-group no-border input-lg">--}}
{{--                                    <div class="input-group-prepend">--}}
{{--                                        <span class="input-group-text text-dark">--}}
{{--                                            <i class="fa fa-envelope"></i></span>--}}
{{--                                    </div>--}}
{{--                                    <input id="email" type="email"--}}
{{--                                        class="form-control @error('email') is-invalid @enderror" name="email"--}}
{{--                                        value="{{ old('email') }}" required autocomplete="email" autofocus--}}
{{--                                        placeholder="Email...">--}}

{{--                                    @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                                <div class="input-group no-border input-lg">--}}
{{--                                    <div class="input-group-prepend">--}}
{{--                                        <span class="input-group-text text-dark">--}}
{{--                                            <i class="fa fa-lock"></i></span>--}}
{{--                                    </div>--}}
{{--                                    <input id="password" type="password"--}}
{{--                                        class="form-control @error('password') is-invalid @enderror" name="password"--}}
{{--                                        required autocomplete="current-password" placeholder="Password...">--}}

{{--                                    @error('password')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="card-footer text-center">--}}
{{--                                <button type="submit" class="btn btn-dark btn-round btn-lg btn-block">--}}
{{--                                    {{ __('Login') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                            <div class="pull-left">--}}
{{--                                @if (Route::has('password.request'))--}}
{{--                                 <a class="link footer-link" href="{{ route('password.request') }}">--}}
{{--                                    {{ __('Forgot Your Password?') }}--}}
{{--                                </a> --}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </form>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--@endsection--}}
