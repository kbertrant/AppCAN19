<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AppCAN19 - Connexion</title>
    <link href={{ asset('img/favicon.png') }} rel="icon">
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
<header class="header black-bg">
    <!--logo start-->
    <a href="{{ route('home') }}" class="logo"><img src="{{ asset('img/logo_appcan19v31.png') }}"> AppCAN19</a>
    <!--logo end-->
    <div class="top-menu">
        <ul class="nav pull-right top-menu">
            <li>
                <a class="logout" href="#" onclick="document.getElementById('logout-form').submit()">QUIT</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>
</header>
<div id="login-page">
    <div class="container">
        <form class="form-login" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
            @csrf
            <h2 class="form-login-heading">LOGIN</h2>
            @if(session('account_error'))
                <div class="alert alert-danger">{{ session('account-error') }}</div>
            @endif
            @if ($errors->has('email'))
                <div class="alert alert-danger">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
            @endif

            <div class="login-wrap">
                <input id="email" type="email" placeholder="Ex: username@yahoo.com"
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                       value="{{ old('email') }}" required autofocus>
                <br>
                <input id="password" type="password" placeholder="Password"
                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                @endif
                <div class="registration" style="line-height:50px;">
                        <a data-toggle="modal" href="{{ route('user.forgot_password') }}">
                            {{ __('Password forgot ?') }}
                        </a>
                    </div>
                <button class="btn btn-theme btn-block" href="index.html" type="submit">
                    <i class="fa fa-lock"></i> {{ __('CONNEXION') }}
                </button>
                <hr>

                <div class="registration">
                    Not registered yet ?<br/>
                    <a class="" style="line-height:25px;" href="{{ route('register') }}">
                        Sign up now
                    </a>
                </div>
            </div>
        </form>
    </div>
<br>
</div>
</body>
<script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.min.js') }}"></script>
<!--BACKSTRETCH-->
<!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
<!--<script type="text/javascript" src="{{ asset('lib/jquery.backstretch.min.js') }}"></script>-->
<script>
    //$.backstretch("img/login-bg.jpg", { speed: 500 });
</script>
<footer class="appcan-bg bloc-bottom">
    <div class="text-center">
            Copyright (c) SolutionsGFF - All rights reserved‎
        <a href="#" class="go-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>
</html>
