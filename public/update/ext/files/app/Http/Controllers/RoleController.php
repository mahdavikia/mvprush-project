<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::latest()->paginate(10);
        return view('admin.roles.index',compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'name' => 'required'
        ],[
            'title.required' => 'عنوان باید درج شود',
            'name.required' => 'نام باید درج شود',
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        Role::create([
            'title' => $request->title,
            'name' => $request->name,
            'active' => $request->active == 'on' ? 1 : 0
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success','نقش با موفقیت ایجاد شد');
    }


    public function show(Role $role)
    {
        return view('admin.roles.show',compact('role'));
    }


    public function edit(Role $role)
    {
        return view('admin.roles.edit',compact('role'));
    }


    public function update(Request $request, Role $role)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'name' => 'required'
        ],[
            'title.required' => 'عنوان باید درج شود',
            'name.required' => 'نام باید درج شود',
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        $role->update($request->all());
        return redirect()->route('admin.roles.index')
            ->with('success','نقش با موفقیت بروزرسانی شد');
    }


    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')
            ->with('success','نقش با موفقیت حذف شد');
    }

}
