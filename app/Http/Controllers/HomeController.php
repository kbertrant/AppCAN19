<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Utils\Utils;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UserFormRequest;
use Illuminate\Support\Facades\Auth;
use App\Team;
use View;


class HomeController extends Controller
{

    public $array_group = array();
    public static $match_joues = array(0 => '1');
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var User $user */
        $choices = DB::select('SELECT * FROM t_team ORDER BY TEAM_NAME ASC');
        return view::make('home', ['choices' => $choices]);
    }

    public function profile()
    {
        /** @var Application $application */

        $id = Auth::user()->id;
        $user = Auth::user();
        $credit = DB::select('SELECT * FROM t_credit WHERE CRED_USER_ID = '.$id.' ORDER BY CRED_ID DESC LIMIT 1');
        $telco_id = $credit[0]->CRED_TELCO_ID;

        return view('user.profile', ['user'=> $user, 'telco_id'=> $telco_id]);
    }

    public function credit()
    {
        /** @var Application $application */
        $id = Auth::user()->id;
        $credit = DB::select('SELECT * FROM t_credit WHERE CRED_USER_ID = '.$id.' ORDER BY CRED_ID DESC LIMIT 1');
        $telco_id = $credit[0]->CRED_TELCO_ID;
        if($telco_id==1){
            $telco = "Orange";
        }else{
            $telco = "MTN";
        }

        return view('credit.credit',['telco'=> $telco]);
    }

    public function payment(Request $request)
    {
        /** @var Application $application */
        $user = Auth::user();

        return view('credit.credit');
    }

    public function forgot_password(){
        return view('user.forgot_password');
    }

    public function menu_main(){

        $user = Auth::user();
        return view('menu_main');
    }


    public function update(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:12', 'min:6'],
            'email' => ['required', 'string', 'email', 'max:100'],
            'password' => ['required','min:6'],
            'phone' => ['required','min:9', 'max:16'],
            'telco' => ['required'],
            'age' => ['required'],
            'city' => ['required'],
            'profession' => ['required'],
            'sport' => ['required'],
            'hobby' => ['required'],
            'gender' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect('profile')
                        ->withErrors($validator)
                        ->withInput();
        }

        $id = Auth::user()->id;
        $update_phone = $request->get('phone');
        $update_phone = implode(explode(' ', $update_phone));
        $telco = $request->get('telco');
        DB::update("UPDATE t_credit SET PHONE_NBR = ".$update_phone.", CRED_TELCO_ID = ".$telco." WHERE CRED_USER_ID = ".$id);
        //dd($id);
        $user = User::findOrFail(Auth::user()->id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->display_password = $request->get('password');
        $user->password = Hash::make($request->get('password'));
        $user->phone = $request->get('phone');
        $user->gender = $request->get('gender');
        $user->age = $request->get('age');
        $user->city = $request->get('city');
        $user->profession = $request->get('profession');
        $user->sport = $request->get('sport');
        $user->hobby = $request->get('hobby');
        $user->save();

        return back();
    }

}

