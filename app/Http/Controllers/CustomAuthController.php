<?php
namespace App\Http\Controllers;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Melorain;

class CustomAuthController extends Controller
{
    public function index()
    {

        if(!Auth::check()){
            return view('admin.login');
        }
        return view('admin.dashboard');
    }

    public function login_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ],[
            'email.required' => 'لطفا ایمیل را وارد کنید',
            'email.email' => 'فرمت ایمیل صحیح نیست',
            'password.required' => 'وارد کردن موضوع اجباریست'
        ]);

        if ($validator->fails()){
//            return view('admin.login',[ "error" => 'نام کاربری یا رمز عبور اشتباه است' ]);
            return back()->withErrors($validator->errors())->withInput();
        }

//        $credentials = $request->only('email', 'password');
        $user_find = User::query()->
                        where('email' , $request->email)->
                        //where('team_id' , $request->team_id)->
                        where('password', Melorain::hash($request->password))->first();
        if($user_find){
            $user = User::find($user_find->id);
            if ($user) {
                Auth::login($user);
                Melorain::userLog('ورود به سیستم');
                return redirect()->route('admin.dashboard');

            }
        }
        $teams = Team::get();
        return view('admin.login',compact('teams'));
    }

    public function logout(Request $request) {
        Melorain::userLog('خروج از سیستم');
        Session::flush();
        Auth::logout();
        $request->session()->forget('IsAuthorized');
        return Redirect()->route('admin.login');
    }
}
