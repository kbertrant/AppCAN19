@extends('main')

@section('title', ' - View Group')

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
                    <h2>GROUPS STAGE</h2>
                    <span class="center">After the TOSS, here are your Group opponents :</span>
                    <table class="table table-hover table-striped">
                    	<thead>
					        <tr>
					            <th style="text-align: center"><h3>{{$group_teams[0]->GRP_NAME}}</h3></th>
					        </tr>
                        </thead>
                        <tbody>
							@foreach($group_teams as $group_team=>$data)
					        	<tr>
                                    @if ($data->TEAM_ID == $id_team)
                                        <td><h4><b>{{ $data->TEAM_NAME }}</b></h4></td>
                                    @else
                                        <td><h4>{{ $data->TEAM_NAME }}</h4></td>
                                    @endif
						        </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('menu_main') }}">
                        {{ __('NEXT') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>
@endsection
