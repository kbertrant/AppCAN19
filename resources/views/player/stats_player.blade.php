@extends('main')

@section('title', ' - Statistics ')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
@endsection

@section('main-content')
    <section class="wrapper">
        <div class="row mt" style="margin-bottom:25px;">
            <div class="col-lg-offset-2 col-lg-6 col-md-6 col-sm-12 ">
                <div class="showback">
                    <h2 class="centered">PLAYERS STATS</h2>

                    <h4 class="centered">TOP SCORERS</h4>
                    <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tableteam">
                        <thead>
                            <tr>
                                <th>RANK</th>
                                <th>PLAYER</th>
                                <th>LINE</th>
                                <th>COUNTRY</th>
                                <th>GOALS</th>
                            </tr>
					    <tbody>
                                @foreach($list_best_scorers as $list_best_scorer)
                                    <tr>
                                        <td><h5>{{$i++}}</b></h5></td>
                                        <td><h5>{{ $list_best_scorer->PLY_NAME }}</h5></td>
                                        <td><h5>{{ $list_best_scorer->LINE_CODE }}</h5></td>
                                        <td><h5>{{ $list_best_scorer->TEAM_NAME }}</h5></td>
                                        <td><h5>{{ $list_best_scorer->GOALS }}</h5></td>
                                    </tr>
                                    <div class="writeinfo"></div>
						        @endforeach
					        </tbody>
					    </thead>
                    </table>
                    </div>
                    <br>
                    <h4 class="centered">TOP ASSISTS</h4>
                    <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tableteam">
                            <thead>
                                <tr>
                                    <th>RANK</th>
                                    <th>PLAYER</th>
                                    <th>LINE</th>
                                    <th>COUNTRY</th>
                                    <th>ASSISTS</th>
                                </tr>
                            <tbody>
                                    @foreach($list_best_assists as $list_best_assist)
                                        <tr>
                                            <td><h5>{{$j++}}</h5></td>
                                            <td><h5>{{ $list_best_assist->PLY_NAME }}</h5></td>
                                            <td><h5>{{ $list_best_assist->LINE_CODE }}</h5></td>
                                            <td><h5>{{ $list_best_assist->TEAM_NAME }}</h5></td>
                                            <td><h5>{{ $list_best_assist->ASSISTS }}</h5></td>
                                        </tr>
                                        <div class="writeinfo"></div>
                                    @endforeach
                                </tbody>
                            </thead>
                        </table>
                    </div>
                        <br>
                    <h4 class="centered">TOP CARDED</h4>
                    <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tableteam">
                            <thead>
                                <tr>
                                    <th>RANK</th>
                                    <th>PLAYER</th>
                                    <th>LINE</th>
                                    <th>COUNTRY</th>
                                    <th>YELLOW</th>
                                    <th>RED</th>
                                </tr>
                            <tbody>
                                    @foreach($list_best_cardeds as $list_best_carded)
                                        <tr>
                                            <td><h5>{{$k++}}</h5></td>
                                            <td><h5>{{ $list_best_carded->PLY_NAME }}</h5></td>
                                            <td><h5>{{ $list_best_carded->LINE_CODE }}</h5></td>
                                            <td><h5>{{ $list_best_carded->TEAM_NAME }}</h5></td>
                                            <td><h5>{{ $list_best_carded->CARDS }}</h5></td>
                                            <td><h5>0</h5></td>
                                        </tr>
                                        <div class="writeinfo"></div>
                                    @endforeach
                                </tbody>
                            </thead>
                        </table>
                    </div>
                        <a class="btn btn-theme btn-block" href="{{ route('team.team_sta') }}" >
                                {{ __('TEAM STATS') }}
                            </a>
                    <a class="btn btn-theme btn-block" href="{{ route('menu_main') }}" >
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
