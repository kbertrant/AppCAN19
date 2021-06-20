@extends('main')

@section('title', ' - All Teams')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
@endsection

@section('main-content')
    <section class="wrapper">
        <div class="row mt" style="margin-bottom:25px;">
            <div class="col-lg-offset-2 col-lg-6 col-md-6 col-sm-12 centered">
                <div class="showback">
                    <h2>TEAMS HATS</h2>
                    <h6 class="center">Teams is grouped by hat and classified by name</h6>

                        <div class="card" style="background-color:#d9edf7; margin-bottom:20px; display:inline; height:auto;">
                            <br>
                            @foreach($list_teams as $cle => $data)

                                <div class="alert alert-dark" style="background-color:#E9E9E9;"><h3> <strong>{{$cle}}</strong> </h></div>
                                <br>
                                <div class="card-body">
                                    <table class="table table-striped centered">
                                        <tbody>
                                        @for ($i = 0; $i < count($data); $i++)
                                            <tr>
                                                @if ($data[$i] == $team)
                                                    <td><h5><b>{{ $data[$i] }}</b></h5></td>
                                                @else
                                                    <td><h5>{{ $data[$i] }}</h5></td>
                                                @endif
                                            </tr>
                                        @endfor
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>

                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('team.view_group') }}" >
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
