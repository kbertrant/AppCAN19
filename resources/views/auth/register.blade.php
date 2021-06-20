<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AppCAN19 - Sign Up</title>
    <link href={{ asset('img/favicon.png') }} rel="icon">
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Phone control core CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-formhelpers.min.css') }}">
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
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
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
<div id="login-page" data-success="{{ session('register-success') ? '1' : '0' }}">
    <div class="container">
        @if (session('register-success'))
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Account Create</h4>
                        </div>
                        <div class="modal-body" style="padding: 30px;">
                            Account create successfully !
                             A confirmation link send to your email address.
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <form class="form-login" style="margin-bottom:25px;" method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
            @csrf
            <h2 class="form-login-heading"> SIGN UP</h2>
            <div class="login-wrap">
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       name="name" value="{{ old('name') }}" placeholder="Ex: John DOE" required autofocus>

                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                @endif
                <br>
                <input id="email" type="email"
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" required
                       value="{{ old('email') }}" placeholder="Ex: john.doe@yahoo.com" required>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                @endif
                <br/>
                <input name="phone" id="phone" placeholder="phone" type="tel"
                       class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"  required />
                <br/>
                <select name="telco" id="telco" class="form-control pro" required >
                        <option value="" ></option>
                    <option value="1">Orange</option>
                    <option value="2">MTN</option>
                </select>
                <br/>
                <select name="age" id="age" class="form-control pro" required>
                        <option value="" ></option>
                        <option value="1" >7 to 10 Years Old</option>
                        <option value="2" >11 to 15 Years Old</option>
                        <option value="3" >16 to 20 Years Old</option>
                        <option value="4" >21 to 25 Years Old</option>
                        <option value="5" >26 to 30 Years Old</option>
                        <option value="6" >31 to 35 Years Old</option>
                        <option value="7" >36 to 40 Years Old</option>
                        <option value="8" >41 to 50 Years Old</option>
                        <option value="9" >51 to 60 Years Old</option>
                        <option value="10">+ 61 Years Old</option>
                    </select>
                    <br/>
                    <select name="gender" id="gender" class="form-control pro" required>
                            <option value="" ></option>
                        <option value="Male">Male </option>
                        <option value="Female" >Female</option>
                    </select>
                    <br/>
                    <select name="city" id="city" class="form-control pro" required>
                        <option value=""></option>
                        <option value="Douala" >Douala </option>
                        <option value="Yaounde" >Yaounde</option>
                        <option value="Bafoussam" >Bafoussam</option>
                        <option value="Bamenda" >Bamenda</option>
                        <option value="Buea" >Buea</option>
                        <option value="Ebolowa" >Ebolowa</option>
                        <option value="Bertoua">Bertoua</option>
                        <option value="Ngaoundere">Ngaoundere</option>
                        <option value="Garoua">Garoua </option>
                        <option value="Maroua">Maroua</option>
                    </select>
                    <br/>
                    <select name="profession" id="profession" class="form-control pro" required>
                            <option value=""></option>
                            <option value="Student">Student</option>
                            <option value="Hand-Worker">Hand-Worker</option>
                            <option value="Unemployed">Unemployed</option>
                            <option value="Seller">Seller</option>
                            <option value="Assistant">Assistant</option>
                            <option value="Manager">Manager</option>
                        </select>

                        <br/>
                        <select name="sport" id="sport" class="form-control pro" required>
                                <option value=""></option>
                            <option value="Football">Football</option>
                            <option value="Basketball">Basketball</option>
                            <option value="Handball">Handball</option>
                            <option value="Volleyball">Volleyball</option>
                            <option value="Tennis">Tennis</option>
                            <option value="Cycling">Cycling</option>
                            <option value="Running">Running</option>
                            <option value="Swimming">Swimming</option>
                            <option value="Martial Art">Martial Art</option>
                        </select>
                        <br/>
                        <select name="hobby" id="hobby" class="form-control pro" required>
                                <option value=""></option>
                            <option value="Music">Music</option>
                            <option value="Cinema">Cinema</option>
                            <option value="Dance">Dance</option>
                            <option value="e-Games">e-Games</option>
                            <option value="Fashion">Fashion</option>
                            <option value="Science">Science</option>
                            <option value="Travel">Travel</option>
                            <option value="Cooking">Cooking</option>
                            <option value="Reading">Reading</option>
                            <option value="Coding">Coding</option>
                        </select>
                <br/>

                <button class="btn btn-theme btn-block" type="submit">
                    <i class="fa fa-lock"></i> SIGN UP
                </button>
                <hr>

                <div class="registration">
                    Already have account ?<br/>
                    <a class="" style="line-height:25px;" href="{{ route('login') }}">
                        Sign in now
                    </a>
                </div>
            </div>
        </form>
    </div>
<br>
</div>
<script src="{{ asset('build/js/intlTelInput.js') }}"></script>
<script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-formhelpers-phone.js') }}"></script>
<script src="{{ asset('js/bootstrap-formhelpers.min.js') }}"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<!--BACKSTRETCH-->
<!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
<!--<script type="text/javascript" src="{{ asset('lib/jquery.backstretch.min.js') }}"></script>-->
<script>

    // $.backstretch("img/login-bg.jpg", { speed: 500 });
    $(function(){
        const authSuccess = $('#login-page').data('success');
        if(authSuccess === 1) {
            $('#myModal').modal('show');
        }
    })


</script>
<script>
var input = document.querySelector("#phone");
console.log(input);
window.intlTelInput(input, {
  initialCountry: "cm",

});
</script>
<footer class="appcan-bg bloc-bottom ">
    <div class="text-center">
        Copyright (c) SolutionsGFF - All rights reserved‎
        <a href="#" class="go-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</footer>
</body>
</html>
