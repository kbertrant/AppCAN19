@extends('main')

@section('title', ' - List players')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
@endsection

@section('main-content')
    <section class="wrapper">

        @include('modal.confirm',['title'=>'Players warning','body'=>'Do you really want to save players changes?'])
        @include('modal.abort',['title'=>'Players warning','body'=>'Do you really want to abort players changes?'])

        <div class="row mt" style="margin-bottom:25px;">
            <div class="col-lg-offset-2 col-lg-6 col-md-6 col-sm-12 centered">
                <div class="showback">
                    <h2>PLAYERS SETTINGS</h2>
                    <h3 class="center">{{ $team_names[0]->TEAM_NAME}}</h3>
                    <h6 class="center">Choose your starting 11 players !</h6>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>LINE</th>
                                <th>PLAYER</th>
                                <th>VALUE</th>
                                <th>GKP</th>
                                <th>DEF</th>
                                <th>MID</th>
                                <th>ATT</th>
                                <th>SEL</th>
                                <th>POS</th>
                            </tr>
                            </thead>
                            <tbody id="players">
                            @if ($p == 0)
                                @foreach($list_players as $list_player=>$data)
                                    <tr class="{{ $data->PLY_ID }}">
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->LINE_CODE }}</td>
                                        <td class="pl" value="{{ $data->PLY_ID }}"><b>{{ $data->PLY_NAME }}</b></td>
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->PLY_VAL }}</td>
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->PLY_GKP_VAL }}</td>
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->PLY_DEF_VAL }}</td>
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->PLY_MID_VAL }}</td>
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->PLY_ATT_VAL }}</td>
                                        <td><input value="{{ $data->PLY_ID }}" type="checkbox" name="PLY_TIT" class="PLY_TIT" onclick="checkStatus(this)"/></td>
                                        <td>
                                            <select class="PLY_POS" name="PLY_POS" id="{{ $data->PLY_ID }}" pl_id="{{ $data->PL_ID }}" onchange="setPosition(this)">
                                                <option value=""></option>
                                                @foreach ($tac_pos as $tac_po)
                                                    <option value="{{ $tac_po->TAC_POS_ID }}-{{ $data->PLY_ID }}">{{ $tac_po->TAC_POS_NAME }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach($list_players as $list_player=>$data)
                                    <tr class="{{ $data->PLY_ID }}">
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->LINE_CODE }}</td>
                                        <td class="pl" value="{{ $data->PLY_ID }}"><b>{{ $data->PLY_NAME }}</b></td>
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->PLY_VAL }}</td>
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->PLY_GKP_VAL }}</td>
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->PLY_DEF_VAL }}</td>
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->PLY_MID_VAL }}</td>
                                        <td class="pl" value="{{ $data->PLY_ID }}">{{ $data->PLY_ATT_VAL }}</td>
                                        <td><input value="{{ $data->PLY_ID }}" type="checkbox" name="PLY_TIT" class="PLY_TIT" onclick="checkStatus(this)"/></td>
                                        <td>
                                            <select class="PLY_POS" name="PLY_POS" id="{{ $data->PLY_ID }}" pl_id="{{ $data->PL_ID }}" onchange="setPosition(this)">
                                                <option value=""></option>
                                                @foreach ($tac_pos as $tac_po)
                                                    @if ($tac_po->TAC_POS_ID == $data->TAC_ID_POS)
                                                        <option value="{{ $tac_po->TAC_POS_ID }}-{{ $data->PLY_ID }}" selected="selected">{{ $tac_po->TAC_POS_NAME }}</option>
                                                    @else
                                                        <option value="{{ $tac_po->TAC_POS_ID }}-{{ $data->PLY_ID }}">{{ $tac_po->TAC_POS_NAME }}</option>
                                                    @endif

                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                    <br>


                    <div class="">
                        <table class="container" style="width:100%">
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12"><a class="btn btn-theme btn-block" href="{{ route('tactic') }}" >{{ __('TACTIC SETTINGS') }}</a></div>
                                        <div class="col-lg-12 col-md-12 col-sm-12" style="margin:5px 0px;">
                                            <a id="idBack" class="btn btn-theme05 btn-block" onclick="checkAbortion();" disabled> {{ __('RETURN') }} </a>
                                            <button id="abortTactic" class="btn btn-primary" data-toggle="modal" data-target="#abortModal" style="display:none;">Open modal</button>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12"><a class="btn btn-theme btn-block" href="#" data-toggle="modal" data-target="#confirmModal"> {{ __('VALID') }} </a></div>
                                    </div>
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
    <script>

        const NB_PLRS = 11;
        let gblPosIds = [];
        let gblPlyIds = [];

        let list_player = [];
        let tac_pos = [];
        let cards = [];
        let p = 0;
        let options = "";

        $("select").attr("disabled", "disabled");
        // $(".caret").remove();

        // -------------------------------------------------------------------------------------------------------------
        $(document).on('ready', function() {

            /*list_player = @json($list_players);
            tac_pos = @json($tac_pos);
            p = @json($p);

            // alert(list_player[0].PLY_ID);
            // document.getElementById('demo').innerHTML = Date()
            if (p === 0) {
                for(var i = 0; i < list_player.length; i++) {
                    options += "<tr>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].LINE_CODE+"</td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\"><b>"+list_player[i].PLY_NAME+"</b></td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].PLY_VAL+"</td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].PLY_GKP_VAL+"</td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].PLY_DEF_VAL+"</td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].PLY_MID_VAL+"</td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].PLY_ATT_VAL+"</td>"+
                        "<td><input value=\""+list_player[i].PLY_ID+"\" type=\"checkbox\" name=\"PLY_TIT\" class=\"PLY_TIT\" onclick=\"checkStatus(this)\"/></td>"+
                        "<td>"+
                        "<select class=\"PLY_POS\" name=\"PLY_POS\">"+
                        "<option value=\"\"></option>";
                    for(var j = 0; j < tac_pos.length; j++) {
                        options += "<option value=\""+tac_pos[j].TAC_POS_ID+"-"+list_player[i].PLY_ID+"\">"+tac_pos[j].TAC_POS_NAME+"</option>";
                    }
                    options += "</select>"+
                        "</td>"+
                        "</tr>";
                }
            }else {
                for(var i = 0; i < list_player.length; i++) {
                    options += "<tr>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].LINE_CODE+"</td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\"><b>"+list_player[i].PLY_NAME+"</b></td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].PLY_VAL+"</td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].PLY_GKP_VAL+"</td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].PLY_DEF_VAL+"</td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].PLY_MID_VAL+"</td>"+
                        "<td class=\"pl\" value=\""+list_player[i].PLY_ID+"\">"+list_player[i].PLY_ATT_VAL+"</td>"+
                        "<td><input value=\""+list_player[i].PLY_ID+"\" type=\"checkbox\" name=\"PLY_TIT\" class=\"PLY_TIT\" onclick=\"checkStatus(this)\"/></td>"+
                        "<td>"+
                        "<select class=\"PLY_POS\" name=\"PLY_POS\">"+
                        "<option value=\"\"></option>";
                    for(var j = 0; j < tac_pos.length; j++) {
                        options += "<option value=\""+tac_pos[j].TAC_POS_ID+"-"+list_player[i].PLY_ID+"\">"+tac_pos[j].TAC_POS_NAME+"</option>";
                        if (tac_pos[j].TAC_POS_ID === list_player[i].TAC_ID_POS) {
                            options += "<option value=\""+tac_pos[j].TAC_POS_ID+"-"+list_player[i].PLY_ID+"\" selected=\"selected\">"+tac_pos[j].TAC_POS_NAME+"</option>";
                        } else {
                            options += "<option value=\""+tac_pos[j].TAC_POS_ID+"-"+list_player[i].PLY_ID+"\">"+tac_pos[j].TAC_POS_NAME+"</option>";
                        }
                    }
                    options += "</select>"+
                        "</td>"+
                        "</tr>";
                }
            }
            // document.getElementById('players').innerHTML = options;
            // alert(options);
            $('#players').html(options);*/

            $(".pl").on('click',function(e){
                e.preventDefault();
                let id = $(this).attr('value');

                $.ajax({
                    url: '/player/player_sta',
                    type: 'GET',
                    dataType: "text",
                    data: {id:id},
                    success: function(result){
                        console.log(id);
                        document.location.href='player_sta/'+id;
                    },
                    error: function(error) {
                        console.log(JSON.stringify(error));
                    }

                });
                $(function(){
                    $(".table").submit();
                });
            });
        });

        // -------------------------------------------------------------------------------------------------------------
        function loadData() {

            gblPosIds = [];
            gblPlyIds = [];

            $('.PLY_POS').each(function() {
                if ($(this).val() !== '' || (($(this).attr('pl_id') !== undefined) && $(this).attr('pl_id') !== null && $(this).attr('pl_id') !== '')) {
                    $(this).prop("disabled", false);
                    $(this).parent().parent().find('.PLY_TIT').attr("checked", "checked");

                    let currentId = $(this).attr('id');
                    let value = $('#'+currentId+' option:selected').val();
                    if ($(this).text() !== '' && value !== '') {
                        let temp = value.split('-');
                        gblPosIds.push(temp[0]);
                        gblPlyIds.push(temp[1]);
                    }

                    // Now we disabled all the option of others select which have a same text
                    $('.PLY_POS').each(function() {
                        let val = $('#'+currentId+' option:selected').text();
                        let selectId = $(this).attr('id');
                        if (currentId !== selectId) {
                            $('#'+selectId+' > option').each(function() {
                                if ((val === $(this).text()) && !$(this).prop("disabled")) {
                                    $(this).prop("disabled", "disabled");
                                    return false;
                                }
                            });
                        }
                    });
                }
            });

            if($('.PLY_TIT').filter(':checked').length === NB_PLRS){
                $('input[name=PLY_TIT]').not(':checked').attr("disabled", true);
                $('#idBack').attr("disabled", false);
            }
        }

        // -------------------------------------------------------------------------------------------------------------
        $(window).on('load', function(){
            loadData();

            // ------------------------------------- Checking red cards section ----------------------------------------
            cards = @json($cards);
            cards.forEach(function(plyCardId) {
                let nbs = $.grep(cards, function (plyId) {
                    return plyId.PLY_ID === plyCardId.PLY_ID;
                }).length;

                if (nbs > 1) {
                    $("." + plyCardId.PLY_ID+" input[name=PLY_TIT]").attr('checked', false);
                    $("." + plyCardId.PLY_ID+" input[name=PLY_TIT]").prop("disabled", "disabled");
                    checkStatus($("." + plyCardId.PLY_ID+" input[name=PLY_TIT]"));
                    let currentId = $("." + plyCardId.PLY_ID).find('.PLY_POS').attr('id');
                    $('#'+currentId+' option:selected').prop("disabled", false);
                    let val = $('#'+currentId+' option:selected').text();
                    let temp = $('#'+currentId+' option:selected').val().split('-');
                    $('#'+currentId).text('');
                    let PosId = temp[0];
                    $('.PLY_POS').each(function() {

                        let selectId = $(this).attr('id');
                        if (currentId !== selectId) {
                            $('#'+selectId+' > option').each(function() {
                                let aux = $(this).val().split('-');
                                let PosId2 = aux[0];
                                if (val === $(this).text() && (PosId === PosId2)) {
                                    $(this).prop("disabled", false);
                                    return false;
                                }
                            });
                        }
                    });
                    $("." + plyCardId.PLY_ID).css('display', 'none');
                }
            });
        });

        // -------------------------------------------------------------------------------------------------------------
        function saveSettings() {
            let tacPosIds = [];
            let plyIds = [];

            $('.PLY_POS option:selected').each(function() {
                if ($(this).val() !== '') {
                    // var elt = $(this);
                    var temp = $(this).val().split('-');
                    tacPosIds.push(temp[0]);
                    plyIds.push(temp[1]);
                }
            });

            if (tacPosIds.length > 0) {
                $.ajax({
                    url: '/player/list_player?step=1',
                    type: 'GET',
                    dataType: "text",
                    data: {
                        tacPosIds: tacPosIds,
                        plyIds: plyIds
                    },
                    success: function (result) {
                        /*loadData();
                        document.location.href='/player/list_player';*/
                        if(document.referrer.includes('next_match') || document.referrer.includes('half_time') || document.referrer.includes('match_end')) {
                            history.back();
                        } else {
                            location.href = "{{route('menu_main')}}";
                        }
                    },
                    error: function (error) {
                        console.log('error:' + JSON.stringify(error));
                    }
                });
            }
        }

        // -------------------------------------------------------------------------------------------------------------
        function checkStatus(obj) {
            if ($(obj).is(':checked')) {
                $(obj).parent().parent().find('.PLY_POS').prop("disabled", false);
            } else {
                let currentId = $(obj).parent().parent().find('.PLY_POS').attr('id');
                $('.PLY_POS').each(function() {
                    let val = $('#'+currentId+' option:selected').text();
                    let temp = $('#'+currentId+' option:selected').val().split('-');
                    let PosId = temp[0];

                    let selectId = $(this).attr('id');
                    if (currentId !== selectId) {
                        $('#'+selectId+' > option').each(function() {
                            let aux = $(this).val().split('-');
                            let PosId2 = aux[0];

                            if ((val === $(this).text()) && (PosId === PosId2) && $(this).prop("disabled")) {
                                $(this).prop("disabled", false);
                                return false;
                            }
                        });
                    }
                });
                $(obj).parent().parent().find('.PLY_POS').prop("disabled", "disabled");
                $(obj).parent().parent().find('.PLY_POS').val("");
            }

            if($('.PLY_TIT').filter(':checked').length === NB_PLRS){
                $('input[name=PLY_TIT]').not(':checked').attr("disabled", true);
            }else{
                $('input[name=PLY_TIT]').not(':checked').attr("disabled", false);
                $('#idBack').attr("disabled", true);

            }
        }

        // -------------------------------------------------------------------------------------------------------------
        function setPosition(obj) {
            $('.PLY_POS').each(function() {
                let currentId = $(obj).attr('id');
                let val = $('#'+currentId+' option:selected').text();
                let temp = $('#'+currentId+' option:selected').val().split('-');
                let PosId = temp[0];

                let selectId = $(this).attr('id');
                if (currentId !== selectId) {
                    $('#'+selectId+' > option').each(function() {
                       let aux = $(this).val().split('-');
                        let PosId2 = aux[0];

                        if (val === $(this).text() && (PosId === PosId2) && !$(this).prop("disabled")) {
                            $(this).prop("disabled", "disabled");
                            return false;
                        }
                    });
                }
            });
        }

        // -------------------------------------------------------------------------------------------------------------
        function confirmAction(choice) {
            if (choice === 1) {
                saveSettings();
            }
        }

        // -------------------------------------------------------------------------------------------------------------
        function checkAbortion() {

            let tacPosIds = [];
            let plyIds = [];

            $('.PLY_POS option:selected').each(function() {
                if ($(this).val() !== '') {
                    let temp = $(this).val().split('-');
                    tacPosIds.push(temp[0]);
                    plyIds.push(temp[1]);
                }
            });

            let quit = false;
            if (tacPosIds.length !== gblPosIds.length){
                quit = true;
            } else {
                $.each(plyIds, function( key, value ) {
                    let index = $.inArray(value, gblPlyIds);
                    if( index === -1 ) {
                        quit = true;
                        return false;
                    }

                    /*if (tacPosIds[key] !== gblPosIds[index]) {
                        quit = true;
                        return false;
                    }*/

                    index = $.inArray(tacPosIds[key], gblPosIds);
                    if (index === -1) {
                        quit = true;
                        return false;
                    }
                });
            }

            if (quit) {
                $('#abortTactic').click();
            } else {
                if(document.referrer.includes('next_match') || document.referrer.includes('half_time') || document.referrer.includes('match_end')) {
                    history.back();
                } else {
                    location.href = "{{route('menu_main')}}";
                }

            }
        }

        // -------------------------------------------------------------------------------------------------------------
        function abortAction(choice) {
            if (choice === 1) {
                if(document.referrer.includes('next_match') || document.referrer.includes('half_time') || document.referrer.includes('match_end')) {
                    history.back();
                } else {
                    location.href = "{{route('menu_main')}}";
                }
            }
        }
        // -------------------------------------------------------------------------------------------------------------

    </script>

@endsection
