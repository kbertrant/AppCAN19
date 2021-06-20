@extends('main')

@section('title', ' - You loose')

@section('stylesheets')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
@endsection

@section('main-content')
    <section class="wrapper">
        <div class="row mt">
            <div class="col-lg-offset-2 col-lg-6 col-md-6 col-sm-12 centered">
                <div class="showback" style="text-align:center;">
                    <h2>Your Ranking</h2>
                    <br>
                    <div class="alert alert-danger">
                        <h6><strong>Warning!</strong> You are not allowed to continue. Please try again!</h6>
                    </div>
                    <br>
                    <table class="table table-striped" id="ranking">
                    	<thead class="appcan-header">
					         <tr>
					            <th>Rank</th>
                                <th>Team</th>
                                <th>Points</th>
					            <th>Win</th>
                                <th>Draw</th>
                                <th>Lose</th>
                                <th>Diff</th>

					        </tr>
                            </thead>
                            <tbody style="text-align:initial;">

                                @foreach ($all_rankigs as $all_ranking)

                                    <tr>
                                        <td>{{ $r++  }}</td>
                                        <td><b>{{ $all_ranking->TEAM_NAME }}</b></td>
                                        <td><b>{{ $all_ranking->TEAM_PTS }}</b></td>
                                        <td>{{ $all_ranking->TEAM_WIN }}</td>
                                        <td>{{ $all_ranking->TEAM_DRAW }}</td>
                                        <td>{{ $all_ranking->TEAM_LOS }}</td>
                                        <td>{{ $all_ranking->TEAM_AVG }}</td>

                                    </tr>
                                @endforeach

                            </tbody>

                    </table>
                    <p id="timer" style="display:none;">Time : <span id="counter">5</span> seconds</p>
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
            if (parseInt(i.innerHTML) === 0) {
                location.href = "{{ route('disconnect')  }}";
            }
            i.innerHTML = parseInt(i.innerHTML)-1;
        }
        setInterval(function(){ countdown(); },1000);
    </script>
@endsection
