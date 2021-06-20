@extends('main')

@section('title', ' - Half time')

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
                    <h3 class="h2_match">{{$grp_stadium}}</h3>
                    <h4 class="h3_match">{{ $grp_name }}</h4>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px;">
                            <img src="{{ asset($countries[$team_name_ht[0]->TEAM_NAME]['IMG'])}}" alt="Team flag" style="width:130px; height:130px; border-radius:80px; border:1px solid;">
                            <br><h3>{{ $countries[$team_name_ht[0]->TEAM_NAME]['NAME'] }}</h3>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px;">
                            <img src="{{ asset($countries[$team_away_ht[0]->TEAM_NAME]['IMG'])}}" alt="Team flag" style="width:130px; height:130px; border-radius:80px; border:1px solid;">
                            <br><h3>{{ $countries[$team_away_ht[0]->TEAM_NAME]['NAME'] }}</h3>

                        </div>
                        <h5 class="h4_match">HALF TIME</h5>
						<h6 class="h5_match">MATCH DETAILS</h6>
                    </div>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="padding:0px;">
                                            <h6><span class="pull-left">{{$but_half1}}</span></h6>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 centered" style="padding:0px;">
                                            <h6><span>GOALS</span></h6>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="padding:0px;">
                                            <h6><span class="pull-right">{{$but_half2}}</span></h6>
                                        </div>
                                    </div>
                                    <div class="">
                                        @if(!empty($players_1))
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px;">
                                                <span class="pull-left">
                                                    @for($i = 0; $i < count($players_1); $i++)
                                                        @if ($i == 0)
                                                            <img src="{{asset('img/ball_main_menu3.png')}}" alt="img" style="height:15px; width:15px; float:left;">&nbsp;{{$times_1[$i]}}' - {{$players_1[$i]}}
                                                        @else
                                                            <br><img src="{{asset('img/ball_main_menu3.png')}}" alt="img" style="height:15px; width:15px; float:left;">&nbsp;{{$times_1[$i]}}' - {{$players_1[$i]}}
                                                        @endif
                                                    @endfor
                                                </span>
                                            </div>
                                        @endif
                                        @if(!empty($players_2))
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px; float:right;">
                                                <span class="pull-right">
                                                    @for($i = 0; $i < count($players_2); $i++)
                                                        @if ($i == 0)
                                                            <img src="{{asset('img/ball_main_menu3.png')}}" alt="img" style="height:15px; width:15px;">&nbsp;{{$times_2[$i]}}' - {{$players_2[$i]}}
                                                        @else
                                                            <br><img src="{{asset('img/ball_main_menu3.png')}}" alt="img" style="height:15px; width:15px;">&nbsp;{{$times_2[$i]}}' - {{$players_2[$i]}}
                                                        @endif
                                                    @endfor
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="padding:0px;">
                                            <h6><span class="pull-left">{{count(array_filter($isViewed_1, function($view) {return $view <= 65;}))}}</span></h6>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 centered" style="padding:0px;">
                                            <h6><span>CARDS</span></h6>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="padding:0px;">
                                            <h6><span class="pull-right">{{count(array_filter($isViewed_2, function($view) {return $view <= 65;}))}}</span></h6>
                                        </div>
                                    </div>
                                    <div class="">
                                        @if(!empty($cards_1))
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px;">
                                                <span class="pull-left">
                                                    @for($i = 0; $i < count($cards_1); $i++)
                                                        @if ($isViewed_1[$i] <= 65)
                                                            @if ($red_cards_1[$i] == 0)
                                                                <img src="{{asset('img/yellow_card_1.jpeg')}}" alt="img" style="height:15px; width:10px; float:left;">&nbsp;{{$cards_time_1[$i]}}' - {{$cards_1[$i]}} <br>
                                                            @else
                                                                <img src="{{asset('img/red_card_1.jpeg')}}" alt="img" style="height:15px; width:10px; float:left;">&nbsp;{{$cards_time_1[$i]}}' - {{$cards_1[$i]}} <br>
                                                            @endif
                                                        @endif
                                                    @endfor
                                                </span>
                                            </div>
                                        @endif
                                        @if(!empty($cards_2))
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px; float:right;">
                                                <span class="pull-right">
                                                    @for($i = 0; $i < count($cards_2); $i++)
                                                        @if ($isViewed_2[$i] <= 65)
                                                            @if ($red_cards_2[$i] == 0)
                                                                <img src="{{asset('img/yellow_card_1.jpeg')}}" alt="img" style="height:15px; width:10px;">&nbsp;{{$cards_time_2[$i]}}' - {{$cards_2[$i]}} <br>
                                                            @else
                                                                <img src="{{asset('img/red_card_1.jpeg')}}" alt="img" style="height:15px; width:10px;">&nbsp;{{$cards_time_2[$i]}}' - {{$cards_2[$i]}} <br>
                                                            @endif
                                                        @endif
                                                    @endfor
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <!--tr>
                                <td><h6><span class="pull-left">0</span> CARDS <span class="pull-right">0</span></h6></td>
                            </tr-->
                            <tr>
                                    <td><h6><span class="pull-left">{{$injuries_half1}}</span> INJURIES <span class="pull-right">{{$injuries_half2}}</span></h6></td>
                            </tr>
                    </table>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('player.list_player') }}" >
                        {{ __('TEAM SETTINGS') }}
                    </a>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('match.match_process2') }}" >
                        {{ __('PLAY SECOND HALF') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>

@endsection
