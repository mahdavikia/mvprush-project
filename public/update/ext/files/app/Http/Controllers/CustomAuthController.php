<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            'password' => 'required',
            'captcha' => 'required|captcha'
        ],[
            'email.required' => 'لطفا ایمیل را وارد کنید',
            'email.email' => 'فرمت ایمیل صحیح نیست',
            'password.required' => 'وارد کردن موضوع اجباریست',
            'captcha.required' => 'کد امنیتی را وارد کنید',
            'captcha.captcha' => 'کد امنیتی صحیح نیست',
        ]);

        if ($validator->fails()){
//            return view('admin.login',[ "error" => 'نام کاربری یا رمز عبور اشتباه است' ]);
            return back()->withErrors($validator->errors())->withInput();
        }

//        $credentials = $request->only('email', 'password');
        $user_find = User::query()->
                        where('email' , $request->email)->
                        where('password', Melorain::hash($request->password))->first();
        if($user_find){
            $user = User::find(1);
            if ($user) {
                Auth::login($user);
                $request->session()->put('IsAuthorized', true);
                return redirect()->route('admin.dashboard');

            }
        }

        return view('admin.login',[ "error" => 'نام کاربری یا رمز عبور اشتباه است' ]);
    }

//    public function create()
//    {
//        return User::create([
//            'email' => 'mahdavikia.m@gmail.com',
//            'password' => Hash::make('123456')
//        ]);
//    }

//    public function registration()
//    {
//        return view('auth.registration');
//    }

//    public function customRegistration(Request $request)
//    {
//        $request->validate([
//            'name' => 'required',
//            'email' => 'required|email|unique:users',
//            'password' => 'required|min:6',
//        ]);
//
//        $data = $request->all();
//        $check = $this->create($data);
//
//        return redirect("dashboard")->withSuccess('You have signed-in');
//    }

//    public function create(array $data)
//    {
//        return User::create([
//            'name' => $data['name'],
//            'email' => $data['email'],
//            'password' => Hash::make($data['password'])
//        ]);
//    }

    public function logout(Request $request) {
        Session::flush();
        Auth::logout();
        $request->session()->forget('IsAuthorized');
        return Redirect()->route('admin.login');
    }
}
