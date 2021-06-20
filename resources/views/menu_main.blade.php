@extends('main')

@section('title', ' - Menu')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
@endsection

@section('main-content')
    <section class="wrapper">
        <div class="row mt" style="margin-bottom:25px;">
            <div class="col-lg-offset-2 col-lg-6 col-md-12 col-sm-12 centered">
                <div class="showback centered">
                    <h2>MENU</h2>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('match.next_match') }}" >
                        <i class="fa fa-play"> </i> {{ __('MATCH') }}
                    </a>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('player.list_player') }}" >
                        <i class="fa fa-cog"> </i> {{ __('SETTINGS') }}
                    </a>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('match.match_result') }}" >
                        <i class="fa fa-calendar"> </i> {{ __('RESULTS') }}
                    </a>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('rank.view_ranking') }}">
                        <i class="fa fa-list"> </i> {{ __('RANKING') }}
                    </a>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('player.stats_player') }}" >
                        <i class="fa fa-th"> </i> {{ __('STATISTICS') }}
                    </a>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('profile') }}" >
                        <i class="fa fa-user"> </i> {{ __('PROFILE') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>
@endsection
