@extends('main')

@section('title', ' - Tactic Settings')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
@endsection

@section('main-content')
    <section class="wrapper">

        @include('modal.confirm',['title'=>'Tactic warning','body'=>'Do you really want to save tactic changes?'])
        @include('modal.abort',['title'=>'Tactic warning','body'=>'Do you really want to abort tactic changes?'])

        <div class="row mt">
            <div class="col-lg-offset-2 col-lg-5 col-md-6 col-sm-12 centered">
                <div class="showback">
                    <h2>TACTIC SETTINGS</h2>
                    <form action="{{url('tactic') }}" method="POST" class="form" role="form">
                    <h6 class="center">Choose the best tactical organization for your Team !</h6>
                 	<select name="choice" class="form-control centered" id="dlist" onchange="getTactic();">
                        @foreach ($tactics as $tactic)
                            @if ($tactic->TAC_ID == $user_tac)
                                <option value="{{ $tactic->TAC_ID }}" selected="selected">{{ $tactic->TAC_CODE }}</option>
                            @else
                                <option value="{{ $tactic->TAC_ID }}">{{ $tactic->TAC_CODE }}</option>
                            @endif
                        @endforeach
                    </select>
                    </form>
                    <br>
                    @if($user_tac == -1)
                        <img id="imageToSwap" src="{{ asset('img/1.png') }}"><br>
                    @else
                        <img id="imageToSwap" src="{{ asset('img/' . $user_tac . '.png ') }}"><br>
                    @endif
                    <br>
                    <div class="">
                        <table class="table">
                            <tr>
                                <td class="col-lg-4 col-md-5 col-sm-6 pull-left" style="padding:5px 0px 5px 0px; border:0px solid;">
                                    <a class="btn btn-theme05 btn-block" onclick="checkAbortion();"> {{ __('RETURN') }} </a>
                                    <button id="abortTactic" class="btn btn-primary" data-toggle="modal" data-target="#abortModal" style="display:none;">Open modal</button>
                                </td>
                                <td class="col-lg-4 col-md-5 col-sm-6 pull-right" style="padding:5px 0px 5px 0px; border:0px solid;">
                                    <a class="btn btn-theme btn-block" href="#" data-toggle="modal" data-target="#confirmModal"> {{ __('VALID') }} </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/bootstrap4/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript">

        var tactic = -1;

        $(window).on('load', function(){
            $.ajax({
                url: '/tactic',
                type: 'GET',
                success: function(result){
                    // document.location.href='/tactic?id='+id;
                },
                error: function(error) {
                    console.log('error:' + JSON.stringify(error));
                }
            });
        });

        // -------------------------------------------------------------------------------------------------------------
        function swapImage(){
            var image = document.getElementById("imageToSwap");
            var dropd = document.getElementById("dlist");

            // console.log(dropd.value);
            var id = dropd.value;
            image.src = "{{asset('img')}}/" + id + ".png";
            $.ajax({
                url: '/tactic?id='+id,
                type: 'GET',
                dataType: "text",
                data: {id:id},
                success: function(result){
                    // document.location.href='/tactic?id='+id;
                    location.href = "{{route('menu_main')}}";
                },
                error: function(error) {
                    console.log('error:' + JSON.stringify(error));
                }
            });
        }

        // -------------------------------------------------------------------------------------------------------------
        function getTactic() {
            tactic = $('#dlist').val();
        }

        // -------------------------------------------------------------------------------------------------------------
        function confirmAction(choice) {
            if (choice === 1) {
                swapImage();
            }
        }

        // -------------------------------------------------------------------------------------------------------------
        function checkAbortion() {
            // alert(tactic + ' ' + {{$user_tac}});
            if ((tactic !== "{{$user_tac}}") && (tactic !== -1)) {
                $('#abortTactic').click();
            } else {
                // history.back();
                document.location.href='/player/list_player';
            }
        }

        // -------------------------------------------------------------------------------------------------------------
        function abortAction(choice) {
            if (choice === 1) {
                // history.back();
                document.location.href='/player/list_player';
            }
        }

    </script>
@endsection
