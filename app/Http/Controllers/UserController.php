<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        $users = User::latest()->paginate(20);
        $roles = Role::where('active',1)->get();
        return view('admin.users.index',compact('users','roles'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }


    public function edit(User $user)
    {
        if(Auth::user()->id !== $user->id && Auth::user()->role_id !== 1){
            return redirect()->route('admin.users.index')
                ->with('error','شما اجازه ورود به پروفایل دیگری را ندارید');
        }
        $roles = Role::where('active',1)->get();
        return view('admin.users.edit',compact('user','roles'));
    }

    public function update(Request $request, $id)
    {

        if(Auth::user()->role_id == 1){
            $validate = Validator::make($request->all(), [
                'email' => 'required|email',
                'firstname' => 'required',
                'lastname' => 'required',
                'avatar' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ],[
                'email.required' => 'نام کاربری را درج نمایید',
                'email.email' => 'فرمت ایمیل صحیح نیست',
                'lastname.required' => 'نام خانوادگی را وارد نمایید',
                'avatar.image' => 'نوع فایل آپلود شده برای نمایه مورد قبول نیست',
                'avatar.max' => 'حجم فایل نمایه بیش از حد مجاز است',
            ]);
            if($validate->fails()){
                return back()->withErrors($validate->errors())->withInput();
            }
        } else {
            $validate = Validator::make($request->all(), [
                'password' => 'required'
            ],[
                'password.required' => 'رمز عبور را تعیین کنید'
            ]);
            if($validate->fails()){
                return back()->withErrors($validate->errors())->withInput();
            }
        }


        $imageName = null;
        $avatar_path = null;
        if(($request->file('avatar')) !== null){
            $imageName = 'avatar_'.time().'.'.$request->avatar->extension();
            $avatar_path = $request->avatar->move(public_path('uploads/avatars'), $imageName);
        }
        if(Auth::user()->role_id == 1) {
            if (!is_null($request->password)) {
                User::where('id', $id)->update([
                    'email' => $request->email,
                    'password' => md5($request->password),
                    'activated' => $request->activated,
                    'role_id' => $request->role_id
                ]);
            } else {
                User::where('id', $id)->update([
                    'email' => $request->email,
                    'activated' => $request->activated,
                    'role_id' => $request->role_id
                ]);
            }
        } else {
            User::where('id', $id)->update([
                'password' => md5($request->password)
            ]);
        }
        if(Auth::user()->role_id == 1) {
            if ($avatar_path !== null) {
                Profile::where('user_id', $id)->update([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'avatar' => $imageName
                ]);
            } else {
                Profile::where('user_id', $id)->update([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname
                ]);
            }
        }
        \Melorain::userLog('Profile was updated');

        return redirect()->route('admin.users.index')
            ->with('success','اطلاعات کاربر با موفقیت ویرایش ایجاد شد');
    }
    public function active(Request $request)
    {
        if(Auth::user()->role_id == 1){
        $u = User::where('id',$request->id)->update([
            'activated' => $request->state
        ]);
        return $u;
        } else {
            return null;
        }
    }
    public function destroy($id)
    {
        User::where('id',$id)->delete();
        Profile::where('user_id',$id)->delete();
        return redirect()->route('admin.users.index')
            ->with('success','کاربر با موفقیت حذف شد');
    }
}
