<?php

namespace App\Http\Controllers;

use App\Models\ControllerBank;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{

    public function index(Role $role)
    {
        $permissions = Permission::where(['role_id' => $role->id])->latest()->paginate(15);
        return view('admin.permissions.index',compact('permissions','role'));
    }


    public function create(Role $role)
    {
        $controller_bank = ControllerBank::all();
        return view('admin.permissions.create',compact('role','controller_bank'));
    }

    public function storeAll(Request $request,Role $role)
    {

        $validate = Validator::make($request->all(), [
            'controller' => 'required',
            'action' => 'required'
        ],[
            'controller.required' => 'عنوان باید درج شود',
            'action.required' => 'نام باید درج شود',
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        $role_id = $role->id;
        $need_actions = $request->action;
        foreach($need_actions as $p){
            $pp= explode('|',$p);
            $cc= explode('|',$request->controller);

            // check exists?
            $per_exist = Permission::where(['route' => $request->controller.'@'.$pp[0]],['role_id' => $role->id])->count();
            if($per_exist<1){
                $per = new Permission();
                $per->role_id = $role->id;
                $per->title = $pp[1].' '.$cc[1];
                $per->route = $cc[0].'@'.$pp[0];
                $per->save();
            }
        }

        return redirect()->route('admin.permissions.index',compact('role'))
            ->with('success','با موفقیت ایجاد شد');
    }



    public function store(Request $request,Role $role)
    {

        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'route' => 'required'
        ],[
            'title.required' => 'عنوان باید درج شود',
            'route.required' => 'مسیر را درج نمایید',
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        Permission::create([
            'role_id' => $role->id,
            'title' => $request->title,
            'route' => $request->route
        ]);
        return redirect()->route('admin.permissions.index',$role)
            ->with('success','دسترسی جدید با موفقیت در این نقش افزوده شد');
    }

    public function edit(Role $role, Permission $permission)
    {
        return view('admin.permissions.edit',compact('role','permission'));
    }


    public function update(Request $request, Role $role, Permission $permission)
    {

        $request->validate([
            'title' => 'required',
            'route' => 'required'
        ]);

        Permission::where('id', $permission->id)->update([
            'title' => $request->title,
            'route' => $request->route
        ]);
        return redirect()->route('admin.permissions.index',$role)
            ->with('success','با موفقیت ویرایش شد');
    }

    public function destroy(Role $role,Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index',$role)
            ->with('success','با موفقیت حذف شد');

    }
}
