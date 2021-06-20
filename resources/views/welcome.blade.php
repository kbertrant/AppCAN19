<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href={{ asset('img/favicon.png') }} rel="icon">

    <title>{{ config('app.name', 'AppCAN19') }} @yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('lib/bootstrap4/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/cover.css') }}" rel="stylesheet">
</head>

<body class="text-center">

<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="masthead mb-auto">
        <div class="inner">
            <h3 class="masthead-brand"><img src="{{ asset('img/logo_appcan19v31.png') }}"> AppCAN19</h3>
            <nav class="nav nav-masthead justify-content-center">
                <a class="nav-link active" href="#">Home</a>
                <a class="nav-link" href="{{ route('login') }}">Login</a>
                <a class="nav-link" href="{{ route('register') }}">Register</a>
            </nav>
        </div>
    </header>

    <main role="main" class="inner cover">
        <img src="{{ asset('img/logo_appcan19_blanc.png') }}">
        <p><small style="font-size:11px">The 2019 Africa Cup of Nations Still in Cameroon.</small></p>
    </main>

    <footer class="mastfoot mt-auto">
        <div class="inner">
            <small>Copyright (c) SolutionsGFF - All rights reserved‎.</small>
        </div>
    </footer>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="{{ asset('lib/jquery-slim.min.js') }}"></script>
<script src="{{ asset('lib/popper.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap4/js/bootstrap.min.js') }}"></script>
</body>
</html>
