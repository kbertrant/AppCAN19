<?php
/**
 * Created by PhpStorm.
 * User: emmaus
 * Date: 18/03/19
 * Time: 09:20
 */
?>
@extends('main')

@section('title', ' - Quarter Crossing')

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
                    <h2>QUARTER FINALS MATCHS</h2>
                    <!--span class="center" style="font-weight:bold">Here are your opponents for  the eight final match<br></span-->
                    <table class="table table-hover table-striped centered">
                    	<thead>
                        </thead>
                        <tbody>
                        @for($i = 0; $i < count($teamsCrossing[0]); $i++)
                            <tr>
                                @if ($teamsCrossing[0][$i] == $team || $teamsCrossing[1][$i] == $team)
                                    @if ($teamsCrossing[0][$i] == $team)
                                        <td class="col-lg-5 col-md-5 col-sm-10"><h4><b>{{ $teamsCrossing[0][$i] }}</b></h4></td>
                                        <td class="col-lg-2 col-md-2 col-sm-2"><h4> - </h4></td>
                                        <td class="col-lg-5 col-md-5 col-sm-10"><h4>{{ $teamsCrossing[1][$i] }}</h4></td>
                                    @else
                                        <td class="col-lg-5 col-md-5 col-sm-10"><h4>{{ $teamsCrossing[0][$i] }}</h4></td>
                                        <td class="col-lg-2 col-md-2 col-sm-2"><h4> - </h4></td>
                                        <td class="col-lg-5 col-md-5 col-sm-10"><h4><b>{{ $teamsCrossing[1][$i] }}</b></h4></td>
                                    @endif
                                @else
                                    <td class="col-lg-5 col-md-5 col-sm-10"><h4>{{ $teamsCrossing[0][$i] }}</h4></td>
                                    <td class="col-lg-2 col-md-2 col-sm-2"><h4> - </h4></td>
                                    <td class="col-lg-5 col-md-5 col-sm-10"><h4>{{ $teamsCrossing[1][$i] }}</h4></td>
                                @endif
                            </tr>
                            <div class="writeinfo"></div>
                        @endfor
                        </tbody>
                    </table>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('match.next_match', array('phase' => 4)) }}">
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
