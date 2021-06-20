<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AppCAN19') }} @yield('title')</title>

    <link href={{ asset('img/favicon.png') }} rel="icon">
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet">

    @yield('stylesheets')
</head>
<body data-uid="{{ Auth::user()->id }}">
<section id="container">
    @include('inc.header')

    <section id="main-content">
        @yield('main-content')
    </section>

    @include('inc.footer')
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="{{ asset('lib/popper.min.js') }}"></script>
<script src="{{ asset('lib/bootstrap/js/bootstrap.min.js') }}"></script>
<script class="include" type="text/javascript" src="{{ asset('lib/jquery.dcjqaccordion.2.7.js') }}"></script>
<script src="{{ asset('lib/holder.min.js') }}"></script>
<script src="{{ asset('lib/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('lib/jquery.nicescroll.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/common-scripts.js') }}"></script>
<script>
    $(function () {
        $.ajaxSetup({
            headers : {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    })
</script>

@yield('scripts')
</body>
</html>
