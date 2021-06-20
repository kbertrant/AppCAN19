@extends('main')

@section('title', ' - Next match')

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
                    <h2 class="h1_match">LIVE MATCH</h2>
                    <h3 class="h2_match">{{ $grp_stadium }}</h3>
                    <h4 class="h3_match">{{ $grp_name }}</h4>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px;">
                            <img src="{{ asset($countries[$team_name[0]->TEAM_NAME]['IMG'])}}" alt="Team flag" style="width:130px; height:130px; border-radius:80px; border:1px solid;">
                            <br><h3>{{ $countries[$team_name[0]->TEAM_NAME]['NAME'] }}</h3>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px;">
                            <img src="{{ asset($countries[$team_away[0]->TEAM_NAME]['IMG'])}}" alt="Team flag" style="width:130px; height:130px; border-radius:80px; border:1px solid;">
                            <br><h3>{{ $countries[$team_away[0]->TEAM_NAME]['NAME'] }}</h3>
                        </div>
                        <h5 class="h4_match">START MATCH</h5>
                        <h6 class="h5_match">MATCH DETAILS</h6>
                    </div>

                    <table class="table table-striped centered">
                        <tbody>
                            <tr>
                                <td><h6><span class="pull-left">0</span>GOALS <span class="pull-right">0</span></h6></td>
                            </tr>
                            <tr>
                                <td><h6><span class="pull-left">0</span> CARDS <span class="pull-right">0</span></h6></td>
                            </tr>
                            <tr>
                                <td><h6><span class="pull-left">0</span> INJURIES <span class="pull-right">0</span></h6></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('player.list_player') }}" >
                        {{ __('TEAM SETTINGS') }}
                    </a>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('match.match_process') }}" >
                        {{ __('PLAY MATCH') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#play_match").on('click',function(e){
                e.preventDefault();
                document.getElementById("title").innerHTML="LIVE MATCH";
            });
        });
    </script>
@endsection
