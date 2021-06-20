<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AppCAN19 - Password identification </title>
    <link href={{ asset('img/favicon.png') }} rel="icon">
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Phone control core CSS -->
    <link rel="stylesheet" href="{{ asset('build/css/intlTelInput.css') }}">

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
            <h1 class="form-login-heading">PASSWORD IDENTIFICATION</h2>
            @if(session('account_error'))
                <div class="alert alert-danger">{{ session('account-error') }}</div>
            @endif
            @if ($errors->has('email'))
                <div class="alert alert-danger">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
            @endif

            <div class="login-wrap">
                <input id="email" type="email" placeholder="Your Email Address"
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                       value="{{ old('email') }}" required autofocus>
                <br>
                <input type="tel" name="phone" id="phone"
                       class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"  required />
                <span id="valid-msg" class="hide">✓ Valid</span>
                <span id="error-msg" class="hide"></span>
                @if ($errors->has('phone'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                @endif

            </br>
                <button class="btn btn-theme btn-block" href="index.html" type="submit">
                     {{ __('VALIDATE') }}
                </button>
                <hr>
            </div>
            <br>
            <!-- Modal -->
            <!--<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Forgot Password ?</h4>
                        </div>
                        <div class="modal-body">
                            <p>Enter your e-mail address below to reset your password.</p>
                            <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                            <button class="btn btn-theme" type="button">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modal -->
        </form>
    </div>
<br>
</div>

<script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.min.js') }}"></script>
<!--BACKSTRETCH-->
<!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
<!--<script type="text/javascript" src="{{ asset('lib/jquery.backstretch.min.js') }}"></script>-->
<script>
    //$.backstretch("img/login-bg.jpg", { speed: 500 });
</script>
<link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <script src="{{ asset('build/js/intlTelInput.js') }}"></script>
    <script>
    // $.backstretch("img/login-bg.jpg", { speed: 500 });
    $(function(){
        const authSuccess = $('#login-page').data('success');
        if(authSuccess === 1) {
            $('#myModal').modal('show');
        }
    })

    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
        utilsScript: "{{ asset('build/js/utils.js') }}",});
</script>
<footer class="appcan-bg bloc-bottom">
    <div class="text-center">
        <p>
            &copy; Copyrights <strong>AppCAN19</strong>. All rights reserved.
        </p>
        <a href="#" class="go-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>
</body>
</html>
