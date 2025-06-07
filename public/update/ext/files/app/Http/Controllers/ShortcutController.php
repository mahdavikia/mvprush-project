<?php

namespace App\Http\Controllers;

use App\Models\Shortcut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShortcutController extends Controller
{

    public function index()
    {
        $shortcuts = Shortcut::paginate(5);
        return view('admin.shortcuts.index',compact('shortcuts'));
    }

    public function create()
    {
        return view('admin.shortcuts.create');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'table' => 'required',
            'sort_order' => 'required'
        ],[
            'title.required' => 'عنوان باید درج شود',
            'table.required' => 'جدول باید درج شود',
            'sort_order' => 'ترتیب باید درج شود'
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        $shortcut = Shortcut::create([
            'title' => $request->title,
            'table' => $request->table,
            'font_color_class' => $request->font_color_class,
            'background_color_class' => $request->background_color_class,
            'icon_class' => $request->icon_class,
            'parameter_name' => $request->parameter_name,
            'parameter_value' => $request->parameter_value,
            'sort_order' => $request->sort_order
        ]);

        return redirect()->route('admin.shortcuts.index')->with('success','رکورد با موفقیت ایجاد شد');
    }

    public function edit(Shortcut $shortcut)
    {
        return view('admin.shortcuts.edit',compact('shortcut'));
    }

    public function update(Request $request, Shortcut $shortcut)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'table' => 'required',
            'sort_order' => 'required'
        ],[
            'title.required' => 'عنوان باید درج شود',
            'table.required' => 'جدول باید درج شود',
            'sort_order' => 'ترتیب باید درج شود'
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        $shortcut = Shortcut::where('id',$shortcut->id)->update([
            'title' => $request->title,
            'table' => $request->table,
            'font_color_class' => $request->font_color_class,
            'background_color_class' => $request->background_color_class,
            'icon_class' => $request->icon_class,
            'parameter_name' => $request->parameter_name,
            'parameter_value' => $request->parameter_value,
            'sort_order' => $request->sort_order
        ]);

        return redirect()->route('admin.shortcuts.index')->with('success','رکورد با موفقیت ویرایش شد');

    }

    public function destroy(Shortcut $shortcut)
    {
        $shortcut->delete();
        return redirect()->route('admin.shortcuts.index')
            ->with('success','رکورد با موفقیت حذف شد');
    }
}
