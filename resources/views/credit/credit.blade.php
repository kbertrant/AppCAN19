@extends('main')

@section('title', ' - Credit')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/bootstrap-fileupload/bootstrap-fileupload.css') }}" />
    <link rel="stylesheet" href="{{ asset('build/css/intlTelInput.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/bootstrap-datepicker/css/datepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/profile.css') }}">
@endsection

@section('main-content')
   <div id="login-page" data-success="{{ session('register-success') ? '1' : '0' }}">
    <div class="container">
        @if (session('register-success'))
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Account Create</h4>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <form class="form-login" method="POST" action="{{ route('payment') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @csrf
            <h2 class="form-login-heading">YOUR CREDIT</h2>
            <h6 class="centered">You actually have {{Utils::get_amount()}} credit(s) ! </h6>

            <div class="login-wrap">
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" disabled
                       name="name" value="{{ old('name') }}" placeholder="{{ Auth::user()->name }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                @endif
                <br>
                <input id="email" type="email"
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" disabled
                       value="{{ old('email') }}" placeholder="{{ Auth::user()->email }}" required>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                @endif
                <br/>
                <input name="phone" id="phone" disabled placeholder="{{ Auth::user()->phone }}"
                       class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"  required />
                <span id="valid-msg" class="hide">✓ Valid</span>
                <span id="error-msg" class="hide"></span>
                @if ($errors->has('phone'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                @endif
                <br/>
                    <input name="telco" id="telco" class="form-control" disabled placeholder="{{$telco}}"/>

                <h6 class="centered">Select your credit to BUY !</h6>
                <select id="payment" name="payment" class="form-control">
                    <option value=""></option>
                    <option value="500">500 FCFA for 2 Credits</option>
                    <option value="1000">1 000 FCFA for 5 Credits</option>
                </select>
                <br/>


                <br/>
                <!--a class="btn btn-theme" href="{{ route('disconnect') }}" >
                    {{ __('RETURN') }}
                </a-->
                @if(app('router')->getRoutes()->match(app('request')->create(URL::previous()))->getName() == 'login' ||
                    app('router')->getRoutes()->match(app('request')->create(URL::previous()))->getName() == 'register')
                    <a class="btn btn-theme" href="{{ route('home') }}" >
                        {{ __('RETURN') }}
                    </a>
                @else
                    <a class="btn btn-theme" href="{{ route('menu_main') }}" >
                        {{ __('RETURN') }}
                    </a>
                @endif

                <button id="btn_pay" class="btn btn-theme pull-right" type="">
                   PAY
                </button>
                <br>
            </div>
        </form>
    </div>
</div><br>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('lib/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/bootstrap-fileupload/bootstrap-fileupload.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('build/js/intlTelInput.js') }}"></script>
<script>
    // $.backstretch("img/login-bg.jpg", { speed: 500 });
    $(function(){
        const authSuccess = $('#login-page').data('success');
        if(authSuccess === 1) {
            $('#myModal').modal('show');
        }
    })

var input = document.querySelector("#phone");
window.intlTelInput(input, {
  initialCountry: "cm",
  geoIpLookup: function(callback) {
    $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
      var countryCode = (resp && resp.country) ? resp.country : "";
      callback(countryCode);
    });
  },
  utilsScript: "{{ asset('build/js/utils.js?1549804213570') }}" // just for formatting/placeholders etc
});
</script>

<script type="text/javascript">
    $("#btn_pay").attr("disabled", "disabled");
    $(document).ready(function () {

        $("#payment").change(function(){
            var id = $('#payment').val();
            if(id==""){
                $("#btn_pay").attr("disabled", "disabled");
            }else{

            $("#btn_pay").attr("disabled", false);}
        });

    });
</script>
@endsection
