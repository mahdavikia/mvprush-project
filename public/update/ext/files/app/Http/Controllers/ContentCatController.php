<?php

namespace App\Http\Controllers;

use App\Models\ContentCat;
use App\Models\ContentCatLang;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContentCatController extends Controller
{
    public function index(Request $request)
    {
        $filter_title = $request->filter_title ?? null;
//        $filter_content_cat_type = $request->filter_content_cat_type ?? 6;// 6= all
//        $filter_active = $request->filter_active ?? 2;// 2 = all

        // filter_content_cat_type storage
        if(!is_null($request->filter_content_cat_type) && $request->filter_content_cat_type !== "0"){
            $request->session()->put('content_cat_filter_category',$request->filter_content_cat_type);
        }
        $filter_content_cat_type = $request->session()->get('content_cat_filter_category') ?? "0";// 0= all
        $request->filter_content_cat_type = $filter_content_cat_type;

        // filter_active storage
        if(!is_null($request->filter_active) && $request->filter_active !== "2"){
            $request->session()->put('content_cat_filter_active',$request->filter_active);
        }
        $filter_active = $request->session()->get('content_cat_filter_active') ?? "2";
        $request->filter_active = $filter_active;

        $content_cats = ContentCat::orderBy('id','desc')
            ->filters($request)
            ->paginate(20);
        return view('admin.content_cats.index',compact('content_cats','filter_title','filter_content_cat_type','filter_active'));
    }

    public function create()
    {
        return view('admin.content_cats.create');
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

        ContentCat::create([
            'title' => $request->title,
            'title_en' => $request->title_en,
            'name' => $request->name,
            'content_cat_type' => $request->content_cat_type,
            'active' => $request->active,
            'sort_order' => $request->sort_order,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('admin.content_cats.index')
            ->with('success','دسته بندی با موفقیت ایجاد شد');
    }

    public function edit(ContentCat $content_cat)
    {
        $content_cat = ContentCat::where('id',$content_cat->id)->first();
        return view('admin.content_cats.edit',compact('content_cat'));
    }

    public function update(Request $request, ContentCat $contentCat)
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
        ContentCat::where('id',$contentCat->id)->update([
            'title' => $request->title,
            'title_en' => $request->title_en,
            'name' => $request->name,
            'content_cat_type' => $request->content_cat_type,
            'active' => $request->active,
            'sort_order' => $request->sort_order,
            'user_id' => Auth::user()->id
        ]);
        return redirect()->route('admin.content_cats.index')
            ->with('success','دسته بندی با موفقیت بروزرسانی شد');
    }

    public function destroy(ContentCat $contentCat)
    {
        Content::where('content_cat_id',$contentCat->id)->update([
            'content_cat_id' => null
        ]);
        $contentCat::delete();
        return redirect()->route('admin.content_cats.index')
            ->with('success','دسته بندی با موفقیت حذف شد');
    }
}
