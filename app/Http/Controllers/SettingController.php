<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ContentsFilesMapping;
use App\Models\File;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{

    public function index()
    {
        $user = User::where('id',Auth::user()->id)->first();
        $settings = Setting::paginate(5);
        return view('admin.settings.index',compact('settings','user'));
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'value' => 'required'
        ],[
            'name.required' => 'نام باید درج شود',
            'value.required' => 'مقدار باید درج شود',
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        $setting = Setting::create([
            'name' => $request->name,
            'value' => $request->value
        ]);

        return redirect()->route('admin.settings.index')->with('success','رکورد با موفقیت ایجاد شد');
    }

    public function edit(Setting $setting)
    {
        return view('admin.settings.edit',compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'value' => 'required'
        ],[
            'name.required' => 'نام باید درج شود',
            'value.required' => 'مقدار باید درج شود',
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        $setting = Setting::where('id',$setting->id)->update([
            'value' => $request->value
        ]);

        return redirect()->route('admin.settings.index')->with('success','رکورد با موفقیت ویرایش شد');

    }

    public function destroy(Setting $setting)
    {
        $setting->delete();
        return redirect()->route('admin.settings.index')
            ->with('success','رکورد با موفقیت حذف شد');
    }
}
