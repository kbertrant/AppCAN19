@extends('main')

@section('title', ' - Live match')

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
                    <h2 class="h1_match">LIVE MATCH </h2>
                    <h3 class="h2_match">{{$grp_stadium}}</h3>
                    <h4 class="h3_match">{{ $grp_name }}</h4>

					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px;">
							<img src="{{ asset($countries[$team_name_pro[0]->TEAM_NAME]['IMG'])}}" alt="Team flag" style="width:130px; height:130px; border-radius:80px; border:1px solid;">
							<br><h3>{{ $countries[$team_name_pro[0]->TEAM_NAME]['NAME'] }}</h3>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px; float:right;">
							<img src="{{ asset($countries[$team_away_pro[0]->TEAM_NAME]['IMG'])}}" alt="Team flag" style="width:130px; height:130px; border-radius:80px; border:1px solid;">
							<br><h3>{{ $countries[$team_away_pro[0]->TEAM_NAME]['NAME'] }}</h3>
                        </div>
                        <h5 class="h4_match">FIRST HALF</h5>
						<h6 class="h5_match" id="timer">Time : <span id="counter">0</span> minutes.</h6>
					</div>
				 	<br>
				 	<table class="table table-striped">
						<tbody>
							<tr>
								<td><h6><span class="pull-left">0</span> GOALS <span class="pull-right">0</span></h6></td>
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
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>
	<script type="text/javascript">
		function countdown() {
    		var i = document.getElementById('counter');
    		if (parseInt(i.innerHTML) === 2) {         // update here add minutes match
				document.getElementById("timer").innerHTML= "Additional time";
        		location.href = "{{ route('match.half_time')  }}";
    		}
    		i.innerHTML = parseInt(i.innerHTML)+1;
		}
		setInterval(function(){ countdown(); },1000);
	</script>
@endsection
