@extends('main')

@section('title', ' - All Results')

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
                    <h2>MATCHS RESULTS</h2>
                   <h6 class="center">Select a match to see details</h6>

                    @foreach($display_data as $key => $data_item)

                        @if ($step == 0)        <!-- This part concerned the group phase of the cup -->
                            @if (!empty($data_item['GROUP A']) && count($data_item['GROUP A']) != 0)
                                <div class="card" style="background-color:#d9edf7; margin-bottom:20px; display:inline; height:auto;">
                                    <h3 class="card-header appcan-header" style="line-height:40px;">{{$key}}</h3>
                                    <br>
                                    @foreach($data_item as $cle => $data)

                                            <div class="alert alert-dark" style="background-color:#E9E9E9;"> <strong>{{$cle}}</strong> </div>
                                            <br>
                                            <div class="card-body">
                                                <table class="table table-striped centered">
                                                    <tbody>
                                                        @for ($i = 0; $i < count($data); $i++)
                                                            <tr>
                                                                @if ($data[$i]['T1'] == $team || $data[$i]['T2'] == $team)
                                                                    @if ($data[$i]['T1'] == $team)
                                                                        <td class="col-lg-4 col-md-4 col-sm-10"><h4><b>{{ $data[$i]['T1'] }}</b></h4></td>
                                                                        <td class="col-lg-1 col-md-1 col-sm-1"><h4 style="float:right;">{{ $data[$i]['B1'] }}</h4></td>
                                                                        <td class="col-lg-1 col-md-1 col-sm-1"><h4> - </h4></td>
                                                                        <td class="col-lg-1 col-md-1 col-sm-1"><h4 style="float:left;">{{ $data[$i]['B2']}}</h4></td>
                                                                        <td class="col-lg-4 col-md-4 col-sm-10"><h4>{{ $data[$i]['T2']}}</h4></td>
                                                                    @else
                                                                        <td class="col-lg-4 col-md-4 col-sm-10"><h4>{{ $data[$i]['T1'] }}</h4></td>
                                                                        <td class="col-lg-1 col-md-1 col-sm-1"><h4 style="float:right;">{{ $data[$i]['B1'] }}</h4></td>
                                                                        <td class="col-lg-1 col-md-1 col-sm-1"><h4> - </h4></td>
                                                                        <td class="col-lg-1 col-md-1 col-sm-1"><h4 style="float:left;">{{ $data[$i]['B2']}}</h4></td>
                                                                        <td class="col-lg-4 col-md-4 col-sm-10"><h4><b>{{ $data[$i]['T2']}}</b></h4></td>
                                                                    @endif
                                                                @else
                                                                    <td class="col-lg-4 col-md-4 col-sm-10"><h4>{{ $data[$i]['T1'] }}</h4></td>
                                                                    <td class="col-lg-1 col-md-1 col-sm-1"><h4 style="float:right;">{{ $data[$i]['B1'] }}</h4></td>
                                                                    <td class="col-lg-1 col-md-1 col-sm-1"><h4> - </h4></td>
                                                                    <td class="col-lg-1 col-md-1 col-sm-1"><h4 style="float:left;">{{ $data[$i]['B2']}}</h4></td>
                                                                    <td class="col-lg-4 col-md-4 col-sm-10"><h4>{{ $data[$i]['T2']}}</h4></td>
                                                                @endif
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                            </div>
                                    @endforeach
                                </div>
                            @endif
                        @else                               <!-- This part concerned the final phase of the cup -->
                            @if(!empty($data_item[0]['T1']))
                                <div class="card" style="background-color:#d9edf7; margin-bottom:20px; display:inline; height:auto;">
                                    <h2 class="card-header appcan-header" style="line-height:50px;">{{$key}}</h2>
                                    <div class="card-body">
                                        <table class="table table-striped centered">
                                            <tbody>
                                            @for ($i = 0; $i < count($data_item); $i++)
                                                <tr>
                                                    <td class="col-lg-4 col-md-4 col-sm-10"><h4>{{ $data_item[$i]['T1'] }}</h4></td>
                                                    <td class="col-lg-1 col-md-1 col-sm-1"><h4 style="float:right;">{{ $data_item[$i]['B1'] }}</h4></td>
                                                    <td class="col-lg-1 col-md-1 col-sm-1"><h4> - </h4></td>
                                                    <td class="col-lg-1 col-md-1 col-sm-1"><h4 style="float:left;">{{ $data_item[$i]['B2']}}</h4></td>
                                                    <td class="col-lg-4 col-md-4 col-sm-10"><h4>{{ $data_item[$i]['T2']}}</h4></td>
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endif

                    @endforeach

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
@endsection
