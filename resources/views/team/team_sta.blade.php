@extends('main')

@section('title', ' - Team Stats')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
@endsection

@section('main-content')
    <section class="wrapper">
        <div class="row mt"  style="margin-bottom:25px">
            <div class="col-lg-offset-2 col-lg-6 col-md-6 col-sm-12 ">

                <div class="showback">
                    <h2 class="centered">TEAM STATS</h2>
                    <h4 class="centered">BEST ATTACK</h4>
                    <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tableteam">
                        <thead>
                            <tr>
                                <th>RANK</th>
                                <th>TEAM</th>
                                <th>SCORED</th>
                            </tr>
					    <tbody>
                                @foreach($list_off_teams as $list_off_team)
                                    <tr>
                                        <td><h5>{{$i++}}</h5></td>
                                        <td><h5>{{ $list_off_team->TEAM_NAME }}</h5></td>
                                        <td><h5>{{ $list_off_team->TEAM_SCO }}</h5></td>

                                    </tr>
                                    <div class="writeinfo"></div>
						        @endforeach
					        </tbody>
					    </thead>
                    </table>
                    </div>
                    <br>
                    <h4 class="centered">BEST DEFENSE</h4>
                    <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tableteam">
                            <thead>
                                <tr>
                                    <th>RANK</th>
                                    <th>TEAM</th>
                                    <th>CONCEDED</th>

                                </tr>
                            <tbody>
                                    @foreach($list_def_teams as $list_def_team)
                                        <tr>
                                            <td><h5>{{$j++}}</h5></td>
                                            <td><h5>{{ $list_def_team->TEAM_NAME }}</h5></td>
                                            <td><h5>{{ $list_def_team->TEAM_CON }}</h5></td>

                                        <div class="writeinfo"></div>
                                    @endforeach
                                </tbody>
                            </thead>
                        </table>
                    </div>
                        <br>
                        <a class="btn btn-theme btn-block" href="{{ route('player.stats_player') }}" >
                            {{ __('RETURN') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>
@endsection
