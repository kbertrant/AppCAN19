<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected $redirectTo = '/credit';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:12', 'min:6'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'phone' => ['required', 'unique:users'],
            'telco' => ['required'],
            'age' => ['required'],
            'city' => ['required'],
            'profession' => ['required'],
            'sport' => ['required'],
            'hobby' => ['required'],
            'gender' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $pass = 'appcan19_'.str_random(8);

        $tel = $data['telco'];
        $phone = $data['phone'];
        $phone = implode(explode(' ', $phone));

        /*User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'display_password' =>$pass,
            'password' => Hash::make($pass),
            'gender'=>$data['gender'],
            'hobby'=>$data['hobby'],
            'sport'=>$data['sport'],
            'profession'=>$data['profession'],
            'city'=>$data['city'],
            'age'=>$data['age'],
            'phone'=>$data['phone'],
        ]);*/
        

        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'display_password' =>$pass,
            'password' => Hash::make($pass),
            'gender'=>$data['gender'],
            'hobby'=>$data['hobby'],
            'sport'=>$data['sport'],
            'profession'=>$data['profession'],
            'city'=>$data['city'],
            'age'=>$data['age'],
            'phone'=>$data['phone'],
        ]);
        $user->save();

        DB::insert('INSERT INTO t_credit (CRED_TELCO_ID, CRED_USER_ID, CRED_BALANCE, PHONE_NBR, created_at, updated_at)
        VALUES (?,?,?,?,?,?)', [$tel, $user->id , 0, $phone, NOW(), NOW()]);

        return $user;


    }
}
