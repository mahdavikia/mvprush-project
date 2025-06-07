<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParameterController extends Controller
{

    public function index()
    {
        $parameters = Parameter::paginate(5);
        return view('admin.parameters.index',compact('parameters'));
    }

    public function create()
    {
        return view('admin.parameters.create');
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
        $parameter = Parameter::create([
            'name' => $request->name,
            'value' => $request->value
        ]);
        if(!is_null($request->stay_here)){
            return redirect()->route('admin.parameters.create')->with('success','رکورد با موفقیت ایجاد شد');
        } else {
            return redirect()->route('admin.parameters.index')->with('success','رکورد با موفقیت ایجاد شد');
        }
    }

    public function edit(Parameter $parameter)
    {
        return view('admin.parameters.edit',compact('parameter'));
    }

    public function update(Request $request, Parameter $parameter)
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
        $parameter = Parameter::where('id',$parameter->id)->update([
            'name' => $request->name,
            'value' => $request->value
        ]);
        if(!is_null($request->stay_here)){
            return redirect()->route('admin.parameters.edit',$parameter)->with('success','رکورد با موفقیت ویرایش شد');
        } else {
            return redirect()->route('admin.parameters.index')->with('success','رکورد با موفقیت ویرایش شد');
        }

    }

    public function destroy(Parameter $parameter)
    {
        $parameter->delete();
        return redirect()->route('admin.parameters.index')
            ->with('success','رکورد با موفقیت حذف شد');
    }
}
