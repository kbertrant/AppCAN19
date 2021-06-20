@extends('main')

@section('title', ' - Profile')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatables/datatables.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('build/css/intlTelInput.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/profile.css') }}">
@endsection

@section('main-content')
   <div id="login-page" data-success="{{ session('register-success') ? '1' : '0' }}">

       @include('modal.confirm',['title'=>'Profile warning','body'=>'Do you really want to save profile changes?'])
       @include('modal.abort',['title'=>'Profile warning','body'=>'Do you really want to abort profile changes?'])

       <div class="container">
        @if (session('register-success'))
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Account Create</h4>
                        </div>
                        <div class="modal-body" style="padding: 30px;">
                            Account create successfully !
                             A confirmation link send to your email address.
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="form-login">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @csrf
            <h2 class="form-login-heading">YOUR PROFILE</h2>

            <div class="login-wrap">
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                       name="name" value="{{ Auth::user()->name }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                @endif
                <br>
                <input id="email" type="email"
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                        value="{{ Auth::user()->email }}" required>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                @endif
                <br/>

                    <div class="has-feedback">
                      <input type="password" name="password" class="form-control" id="password" value="{{ Auth::user()->display_password }}" required>
                      <i class="glyphicon glyphicon-eye-open form-control-feedback"></i>
                      <span id="valid-msg" class="hide">✓ Valid</span>
                        <span id="error-msg" class="hide"></span>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                @endif
                    </div>

                  <br/>
                <input type="tel" name="phone" id="phone" value="{{ Auth::user()->phone }}"
                       class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"  required />
                <span id="valid-msg" class="hide">✓ Valid</span>
                <span id="error-msg" class="hide"></span>
                @if ($errors->has('phone'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                @endif

                <br/>

                <select name="telco" id="telco" class="form-control pro" required>
                    <option value="1" {{ ($telco_id == 1) ? 'selected' : '' }}>Orange</option>
                    <option value="2" {{ ($telco_id == 2) ? 'selected' : '' }}>MTN </option>

                </select>
                <br/>
                <select name="age" id="age" class="form-control pro" required>
                    <option value="1" {{ ($user->age == 1) ? 'selected' : '' }}>7 to 10 Years Old</option>
                    <option value="2" {{ ($user->age == 2) ? 'selected' : '' }}>11 to 15 Years Old</option>
                    <option value="3" {{ ($user->age == 3) ? 'selected' : '' }}>16 to 20 Years Old</option>
                    <option value="4" {{ ($user->age == 4) ? 'selected' : '' }}>21 to 25 Years Old</option>
                    <option value="5" {{ ($user->age == 5) ? 'selected' : '' }}>26 to 30 Years Old</option>
                    <option value="6" {{ ($user->age == 6) ? 'selected' : '' }}>31 to 35 Years Old</option>
                    <option value="7" {{ ($user->age == 7) ? 'selected' : '' }}>36 to 40 Years Old</option>
                    <option value="8" {{ ($user->age == 8) ? 'selected' : '' }}>41 to 50 Years Old</option>
                    <option value="9" {{ ($user->age == 9) ? 'selected' : '' }}>51 to 60 Years Old</option>
                    <option value="10" {{ ($user->age == 10) ? 'selected' : '' }}>+ 61 Years Old</option>
                </select>
                <br/>
                <select name="gender" id="gender" class="form-control pro" required>
                    <option value="Male" {{ ($user->gender == "Male") ? 'selected' : '' }}>Male </option>
                    <option value="Female" {{ ($user->gender == "Female") ? 'selected' : '' }}>Female</option>
                </select>
                <br/>
                <select name="city" id="city" class="form-control pro" required>
                    <option value="Douala" {{ ($user->city == "Douala") ? 'selected' : '' }}>Douala </option>
                    <option value="Yaounde" {{ ($user->city == "Yaounde") ? 'selected' : '' }}>Yaounde</option>
                    <option value="Bafoussam" {{ ($user->city == "Bafoussam") ? 'selected' : '' }}>Bafoussam</option>
                    <option value="Bamenda" {{ ($user->city == "Bamenda") ? 'selected' : '' }}>Bamenda</option>
                    <option value="Buea" {{ ($user->city == "Buea") ? 'selected' : '' }}>Buea</option>
                    <option value="Ebolowa" {{ ($user->city == "Ebolowa") ? 'selected' : '' }}>Ebolowa</option>
                    <option value="Bertoua" {{ ($user->city == "Bertoua") ? 'selected' : '' }}>Bertoua</option>
                    <option value="Ngaoundere" {{ ($user->city == "Ngaoundere") ? 'selected' : '' }}>Ngaoundere</option>
                    <option value="Garoua" {{ ($user->city == "Garoua") ? 'selected' : '' }}>Garoua </option>
                    <option value="Maroua" {{ ($user->city == "Maroua") ? 'selected' : '' }}>Maroua</option>
                </select>
                <br/>
                <select name="profession" id="profession" class="form-control pro" required>
                    <option value="Student" {{ ($user->profession == "Student") ? 'selected' : '' }}>Student</option>
                    <option value="Hand-Worker" {{ ($user->profession == "Hand-Worker") ? 'selected' : '' }}>Hand-Worker</option>
                    <option value="Unemployed" {{ ($user->profession == "Unemployed") ? 'selected' : '' }}>Unemployed</option>
                    <option value="Seller" {{ ($user->profession == "Seller") ? 'selected' : '' }}>Seller</option>
                    <option value="Assistant" {{ ($user->profession == "Assistant") ? 'selected' : '' }}>Assistant</option>
                    <option value="Manager" {{ ($user->profession == "Manager") ? 'selected' : '' }}>Manager</option>
                </select>

                <br/>
                <select name="sport" id="sport" class="form-control pro" required>
                    <option value="Football" {{ ($user->sport == "Football") ? 'selected' : '' }}>Football</option>
                    <option value="Basketball" {{ ($user->sport == "Basketball") ? 'selected' : '' }}>Basketball</option>
                    <option value="Handball" {{ ($user->sport == "Handball") ? 'selected' : '' }}>Handball</option>
                    <option value="Volleyball" {{ ($user->sport == "Volleyball") ? 'selected' : '' }}>Volleyball</option>
                    <option value="Tennis" {{ ($user->sport == "Tennis") ? 'selected' : '' }}>Tennis</option>
                    <option value="Cycling" {{ ($user->sport == "Cycling") ? 'selected' : '' }}>Cycling</option>
                    <option value="Running" {{ ($user->sport == "Running") ? 'selected' : '' }}>Running</option>
                    <option value="Swimming" {{ ($user->sport == "Swimming") ? 'selected' : '' }}>Swimming</option>
                    <option value="Martial Art" {{ ($user->sport == "Martial Art") ? 'selected' : '' }}>Martial Art</option>
                </select>
                <br/>
                <select name="hobby" id="hobby" class="form-control pro" required>
                    <option value="Music" {{ ($user->hobby == "Music") ? 'selected' : '' }}>Music</option>
                    <option value="Cinema" {{ ($user->hobby == "Cinema") ? 'selected' : '' }}>Cinema</option>
                    <option value="Dance" {{ ($user->hobby == "Dance") ? 'selected' : '' }}>Dance</option>
                    <option value="e-Games" {{ ($user->hobby == "e-Games") ? 'selected' : '' }}>e-Games</option>
                    <option value="Fashion" {{ ($user->hobby == "Fashion") ? 'selected' : '' }}>Fashion</option>
                    <option value="Science" {{ ($user->hobby == "Science") ? 'selected' : '' }}>Science</option>
                    <option value="Travel" {{ ($user->hobby == "Travel") ? 'selected' : '' }}>Travel</option>
                    <option value="Cooking" {{ ($user->hobby == "Cooking") ? 'selected' : '' }}>Cooking</option>
                    <option value="Reading" {{ ($user->hobby == "Reading") ? 'selected' : '' }}>Reading</option>
                    <option value="Coding" {{ ($user->hobby == "Coding") ? 'selected' : '' }}>Coding</option>
                </select>
                <br/>
                <div class="">
                    <table class="table">
                        <tr>
                            <td class="col-lg-4 col-md-5 col-sm-6 pull-left" style="padding:5px 0px 5px 0px; border:0px solid;">
                                <a class="btn btn-theme05 btn-block" href="#" onclick="checkAbortion();"> {{ __('RETURN') }} </a>
                                <button id="abortTactic" class="btn btn-primary" data-toggle="modal" data-target="#abortModal" style="display:none;">Open modal</button>
                            </td>
                            <td class="col-lg-4 col-md-5 col-sm-6 pull-right" style="padding:5px 0px 5px 0px; border:0px solid;">
                                <a class="btn btn-theme btn-block" href="#" data-toggle="modal" data-target="#confirmModal"> {{ __('VALID') }} </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
            </div>
        </div>
    </div>
</div><br>
@endsection

@section('scripts')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/hideshowpassword/2.0.8/hideShowPassword.min.js"></script>
    <script src="{{ asset('build/js/intlTelInput.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/popper.min.js') }}"></script>
    <script>

        $.noConflict();

        $('#password + .glyphicon').on('click', function() {
          $(this).toggleClass('glyphicon-eye-close').toggleClass('glyphicon-eye-open'); // toggle our classes for the eye icon
          $('#password').togglePassword(); // activate the hideShowPassword plugin
        });
        // $.backstretch("img/login-bg.jpg", { speed: 500 });
        $(function(){
            const authSuccess = $('#login-page').data('success');
            if(authSuccess === 1) {
                $('#myModal').modal('show');
            }
        });

        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
        initialCountry: "cm",
        });

        // -------------------------------------------------------------------------------------------------------------
        function confirmAction(choice) {
            if (choice === 1) {
                // $('.form-login').submit();

                var name = $('#name').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var phone = $('#phone').val();
                var telco = $('#telco').val();
                var age = $('#age').val();
                var gender = $('#gender').val();
                var city = $('#city').val();
                var profession = $('#profession').val();
                var sport = $('#sport').val();
                var hobby = $('#hobby').val();

                $.ajax({
                    url: '/profile',
                    type: 'POST',
                    dataType: "text",
                    data: {
                        name: name,
                        email: email,
                        password: password,
                        phone: phone,
                        telco: telco,
                        age: age,
                        gender: gender,
                        city: city,
                        profession: profession,
                        sport: sport,
                        hobby: hobby
                    },
                    success: function (result) {
                        document.location.href='/profile';
                    },
                    error: function (error) {
                        console.log('error:' + JSON.stringify(error));
                    }
                });
            }
        }

        // -------------------------------------------------------------------------------------------------------------
        function checkAbortion() {

            var quit = false;

            var name = $('#name').val();
            var email = $('#email').val();
            var password = $('#password').val();
            var phone = $('#phone').val();
            var telco = $('#telco').val();
            var age = $('#age').val();
            var gender = $('#gender').val();
            var city = $('#city').val();
            var profession = $('#profession').val();
            var sport = $('#sport').val();
            var hobby = $('#hobby').val();

            if ((name !== "{{$user->name}}") || (email !== "{{$user->email}}") || (password !== "{{$user->display_password}}")) {
                quit = true;
            }
            if ((phone !== "{{$user->phone}}") || (telco !== "{{$telco_id}}") || (age !== "{{$user->age}}")) {
                quit = true;
            }
            if ((gender !== "{{$user->gender}}") || (city !== "{{$user->city}}") || (profession !== "{{$user->profession}}")) {
                quit = true;
            }
            if ((sport !== "{{$user->sport}}") || (hobby !== "{{$user->hobby}}")) {
                quit = true;
            }

            if (quit) {
                $('#abortTactic').click();
            } else {
                location.href = "{{ route('menu_main') }}";
                // history.back();
            }
        }

        // -------------------------------------------------------------------------------------------------------------
        function abortAction(choice) {
            if (choice === 1) {
                location.href = "{{ route('menu_main') }}";
                // history.back();
            }
        }
    </script>
@endsection
