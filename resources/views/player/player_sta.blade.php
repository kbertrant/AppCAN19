@extends('main')

@section('title', ' - Player Stat')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
@endsection

@section('main-content')
    <section class="wrapper">
        <div class="row mt">
            <div class="col-lg-offset-3 col-lg-5 col-md-6 col-sm-12 centered">
                <div class="showback">
                    <h2>PLAYER STAT</h2>
                    <h6 class="center">Data recorded in the last match</h6><br>


            		<h2>{{ $pl_sta[0]->PLY_NAME }}</h2>
            		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 center" style="text-align:left;">

            		<h4>Position : <b>{{ $pl_sta[0]->LINE_CODE }}</b></h4>
                    <h4>Overall rating : <b>{{ $pl_sta[0]->PLY_VAL }}</b></h4>

                    <h4>Country : <b>{{ $pl_sta[0]->TEAM_NAME }}</b></h4>
                    <img src="{{ asset($countries[$pl_sta[0]->TEAM_NAME]['IMG'])}}" alt="Team flag" style="padding-bottom:5px;">

                    <br>
            		</div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 center" style="text-align:right;">
                        @if($pl_sta[0]->LINE_CODE == "GKP")
                            <img src="{{ asset('img/avatar_foot_gkp.jpg') }}">
                        @else
                            <img src="{{ asset('img/avatar_foot.jpg') }}">
                        @endif

                 	</div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td><span class="pull-left">Goals</span> <span class="pull-right"><b>{{ $pl_sta[0]->PLY_SCO }}</b></span></td>
                                <td><span class="pull-left">Substitutes</span> <span class="pull-right"><b>{{ $pl_sta[0]->PLY_SUB }}</b></span></td>

                            </tr>
                            <tr>
                                <td><span class="pull-left">Assists</span> <span class="pull-right"><b>{{ $pl_sta[0]->PLY_ASS }}</b></span></td>
                                <td><span class="pull-left">Cards</span> <span class="pull-right"><b>{{ $pl_sta[0]->PLY_CRD }}</b></span></td>

                            </tr>
                            <tr>
                                <td><span class="pull-left">Shape</span> <span class="pull-right"><b>{{ $pl_sta[0]->PLY_SHP }}</b></span></td>
                                <td><span class="pull-left">Injuries</span> <span class="pull-right"><b>{{ $pl_sta[0]->PLY_INJ }}</b></span></td>

                            </tr>
                        </tbody>

                    </table>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('player.list_player') }}" >
                        {{ __('RETURN') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>
@endsection
