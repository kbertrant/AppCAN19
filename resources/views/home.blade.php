@extends('main')

@section('title', ' - Choice team')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
@endsection

@section('main-content')
    <section class="wrapper">
        <div class="row mt">
            <div class="form" role="form">
                <div class="col-lg-offset-2 col-lg-6 col-md-6 col-sm-12 centered">
                    {{csrf_field()}}
                    <div class="showback">
                        <h2>TEAM CHOICE</h2>
                        <h6 class="center">Choose your favourite country for the competition</h6>
                        <img src="{{ asset('img/logo_appcan19v3.png') }}" alt="img"/>
                        <br>
                        <select id="choice" name="choice" class="form-control centered">
                            @foreach ($choices as $choice)
                                <option value="{{ $choice->TEAM_ID }}">{{ $choice->TEAM_NAME }}</option>
                            @endforeach
                        </select>
                        <br>
                        <button class="btn btn-theme btn-block" type="button" onclick="goToTeamsHats();">
                             {{ __('VALIDATE') }}
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>
    <script>
        function goToTeamsHats() {
            let idTeam = $('#choice option:selected').val();
            $.ajax({
                url: '/team/list_team',
                type: 'POST',
                dataType: "text",
                data: {idTeam:idTeam},
                success: function(result){
                    location.href = '/team/list_team';
                },
                error: function(error) {
                    console.log(JSON.stringify(error));
                }

            });
        }
    </script>
@endsection
