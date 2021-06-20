@extends('main')

@section('title', ' - Ranking')

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
                    <h2>RANKING</h2>
                    <h6 class="center">Select a group to see the ranking</h6>
                    <br>
					<select class="form-control" name="grp_name" id="grp_name">
                        @foreach ($groups as $group)
                            @if ($group->GRP_ID == $usr_grp)
                                <option value="{{ $group->GRP_ID }}" selected="selected">{{ $group->GRP_NAME }}</option>
                            @else
                                <option value="{{ $group->GRP_ID }}">{{ $group->GRP_NAME }}</option>
                            @endif
                        @endforeach
                    </select>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped table-responsive" id="ranking">
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
                                        @if ($all_ranking->TEAM_NAME == $team)
                                            <td><b>{{ $all_ranking->TEAM_NAME }}</b></td>
                                        @else
                                            <td>{{ $all_ranking->TEAM_NAME }}</td>
                                        @endif
                                        <td><b>{{ $all_ranking->TEAM_PTS }}</b></td>
                                        <td>{{ $all_ranking->TEAM_WIN }}</td>
                                        <td>{{ $all_ranking->TEAM_DRAW }}</td>
                                        <td>{{ $all_ranking->TEAM_LOS }}</td>
                                        <td>{{ $all_ranking->TEAM_AVG }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
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

    <script type="text/javascript">

        $(document).ready(function() {

            $("#grp_name").change(function(){
                console.log($('#grp_name').val());
                var id = $('#grp_name').val();
                $.ajax({
                    url: '/rank/view_ranking?id='+id,
                    type: 'GET',
                    data: {id:id},
                    success: function(result){
                        // console.log(id);
                        document.location.href='/rank/view_ranking?id='+id;
                    },
                    error: function(error) {
                        alert('error:' + JSON.stringify(error));
                    }
                });
                $(function(){
                    $("#grp_name").submit();
                });
            });

        });

        $(window).on('load', function(){

            var urlParams = new URLSearchParams(window.location.search);
            if (!urlParams.has('id')) {
                $.ajax({
                    url: '/rank/view_ranking',
                    type: 'GET',
                    success: function(result){
                        console.log('Ranking page')
                    },
                    error: function(error) {
                        alert('error:' + JSON.stringify(error));
                    }
                });
            } else {
                id = urlParams.get('id');
                $.ajax({
                    url: '/rank/view_ranking?id='+id,
                    type: 'GET',
                    data: {id:id},
                    success: function(result){
                        //console.log(id);
                        //document.location.href='/rank/view_ranking?id='+id;
                    },
                    error: function(error) {
                        alert('error:' + JSON.stringify(error));
                    }
                });
                $(function(){
                    $("#grp_name").submit();
                });
            }
        });

    </script>
@endsection
