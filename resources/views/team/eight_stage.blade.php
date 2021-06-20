<?php
/**
 * Created by PhpStorm.
 * User: emmaus
 * Date: 15/03/19
 * Time: 14:07
 */
?>
@extends('main')

@section('title', ' - Eight Final Stage')

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
                    <h2>EIGHTH FINALS STAGE</h2>
                    <h6 class="center" >Here are the Teams qualified for the Finals Stage :<br></h6>
                    <table class="table table-hover table-striped">
                        <tbody>
                            @foreach($list_teams as $list_team=>$data)
                                <tr>
                                    @if ($data->TEAM_ID == Auth::user()->TEAM_ID)
                                        <td><h4><b>{{ $data->TEAM_NAME }}</b></h4></td>
                                    @else
                                        <td><h4>{{ $data->TEAM_NAME }}</h4></td>
                                    @endif
                                </tr>
                                <div class="writeinfo"></div>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <a class="btn btn-theme btn-block" href="{{ route('team.eight_crossing') }}">
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
