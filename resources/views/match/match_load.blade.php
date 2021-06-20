@extends('main')

@section('title', ' - Load match')

@section('stylesheets')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
@endsection

@section('main-content')
	<section class="wrapper">
        <div class="row mt">
            <div class="col-lg-offset-2 col-lg-6 col-md-6 col-sm-12 centered">
                <div class="showback">
                    <h1>CONGRATULATONS !!!</h1>
                    <h6>You have won the african cup of nations 2019.</h6>
                    <br>
                    <img src="{{ asset('img/cup_appcan.png ') }}"><br>
                    <a class="btn btn-theme btn-block" href="{{ route('menu_main') }}" >
                        {{ __('MENU') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>
@endsection
