<?php

namespace App\Http\Controllers;

use App\Models\ContentCat;
use App\Models\ContentsFilesMapping;
use App\Models\File;
use App\Models\Morefield;
use App\Models\MorefieldMapping;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Melorain;

class NewsController extends Controller
{

    public function index(Request $request)
    {
        $filter_title = $request->filter_title ?? null;
//        $filter_category = $request->filter_category ?? 0;// 0= all
//        $filter_active = $request->filter_active ?? 2;// 2 = all

        // filter_category storage
        if(!is_null($request->filter_category)){
            $request->session()->put('news_filter_category',$request->filter_category);
        } else {
            $request->filter_category = 0;
        }
        $filter_category = $request->session()->get('news_filter_category') ?? "0";// 0= all
        $request->filter_category = $filter_category;

        // filter_active storage
        if(!is_null($request->filter_active)){
            $request->session()->put('news_filter_active',$request->filter_active);
        } else {
            $request->filter_active = 2;
        }
        $filter_active = $request->session()->get('news_filter_active') ?? "2";
        $request->filter_active = $filter_active;

        $news_list = News::orderBy('id','desc')->filters($request)->paginate(10);
        $categories = ContentCat::whereIn('content_cat_type',[4,5])->get();
        return view('admin.news.index',compact('news_list','categories','filter_title','filter_category','filter_active'));
    }

    public function create()
    {
        $content_cats = ContentCat::where('active',1)
            ->whereIn('content_cat_type',[4,5])
            ->get();
        $morefields = Morefield::select('title')->get();
        return view('admin.news.create',compact('content_cats','morefields'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'docs.*' => 'mimes:pdf,doc,docx,mp3,mp4,xls,xlsx',
        ],[
            'images.image' => 'نوع فایل عکس صحیح نیست',
            'images.mimes' => 'فرمت فایل صحیح نیست',
            'docs.mimes' => 'فرمت فایل صحیح نیست',
            'images.max' => 'حجم فایل نمایه بیش از حد مجاز است',
            'image.image' => 'نوع فایل عکس صحیح نیست',
            'image.mimes' => 'فرمت فایل صحیح نیست',
            'image.max' => 'حجم فایل نمایه بیش از حد مجاز است',
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        $imageName = null;
        if($request->hasFile('image')){
            $filename = md5($request->image->getClientOriginalName());
            $imageName = 'news_'.$filename.'.'.$request->image->extension();
            $avatar_path = $request->image->move(public_path('uploads/news/'), $imageName);
        }

        $news = News::create([
                'content_cat_id' => $request->content_cat_id,
                'active' => $request->active,
                'title' => $request->title,
                'title_en' => $request->title_en ?? null,
                'brief' => $request->brief ?? null,
                'brief_en' => $request->brief_en ?? null,
                'content' => $request->content ?? null,
                'content_en' => $request->content_en ?? null,
                'name' => $request->name ?? null,
                'share' => $request->share ?? null,
                'user_id' => Auth::user()->id,
                'image' => $imageName,
                'expire_at' => Melorain::jalali_to_gregorian($request->expire_at) ?? null
            ]);
        if ($request->images){
            foreach($request->images as $file) {

                $filename = md5($file->getClientOriginalName());
                $imageName = 'image_'.$filename.'.'.$file->extension();

                $avatar_path = $file->move(public_path('uploads/news/'.$news->id.'/'), $imageName);
                // // Create files
                if($avatar_path){
                    $f = File::create([
                        'file' => $imageName,
                        'type' => 'img',
                        'user_id' => Auth::user()->id
                    ]);
                    ContentsFilesMapping::create([
                        'user_id' => Auth::user()->id,
                        'content_id' => $news->id,
                        'file_id' => $f->id,
                        'type' => 'news'
                    ]);
                    $imageName = null;
                }
            }
        }
        if ($request->docs){
            foreach($request->docs as $f) {
                $filename = md5($f->getClientOriginalName());
                $fname = $f->getClientOriginalName();
                $fname_arr = explode('.',$fname);
                $imageName = 'doc_' . $filename . '.' . end($fname_arr);
                $avatar_path = $f->move(public_path('uploads/news/' . $news->id . '/'), $imageName);
                // // Create files
                if ($avatar_path) {
                    $ff = File::create([
                        'file' => $imageName,
                        'type' => 'doc',
                        'user_id' => Auth::user()->id
                    ]);
                    ContentsFilesMapping::create([
                        'user_id' => Auth::user()->id,
                        'content_id' => $news->id,
                        'file_id' => $ff->id,
                        'type' => 'news'
                    ]);
                    $imageName = null;
                }
            }
        }

        if(!is_null($request->morefield_title)){
            $new_morefields_title = $request->morefield_title;
            $new_morefields_value = $request->morefield_value;
            $old_morefields = Morefield::pluck('title')->toArray();
            $c=0;
            foreach ($new_morefields_title as $morefield){
                if(!in_array($morefield, $old_morefields)){
                    Morefield::create([
                        'title' => $morefield
                    ]);
                }
            }
        }
        $c=0;
        if(!is_null($request->morefield_title)){
            foreach ($new_morefields_title as $morefield){
                $morefield_record = Morefield::where('title',$morefield)->first();
                MorefieldMapping::create([
                    'user_id' => Auth::user()->id,
                    'table_name' => 'news',
                    'table_record_id' => $news->id,
                    'morefield_id' => $morefield_record->id,
                    'value' => $new_morefields_value[$c]
                ]);
                $c++;
            }
        }

        return redirect()->route('admin.news.index')->with('success','رکورد با موفقیت ایجاد شد');
    }

    public function edit(News $news)
    {
        // content_cat_type : 1 => محتوا
        $content_cats = ContentCat::where('active',1)
            ->whereIn('content_cat_type',[4,5])
            ->get();
        $images = $this->getImages($news->id);
        $files = $this->getDocs($news->id);
        if(!is_null($news->expire_at)){
            $news->expire_at = Melorain::gregorian_to_jalali($news->expire_at);
        }
        $morefields = Morefield::select('title')->get();
        $morefields_full = MorefieldMapping::select([
            'morefield_mappings.id as id',
            'morefield_mappings.value',
            'morefields.title as title'
        ])->where([
            'table_name' => 'news',
            'table_record_id' => $news->id
        ])
            ->leftJoin('morefields','morefields.id','=','morefield_mappings.morefield_id')
            ->get();

        return view('admin.news.edit',compact('news','content_cats','files','images','morefields','morefields_full'));
    }
    public function image_set_main(News $news,File $file){
        $files = ContentsFilesMapping::where('content_id',$news->id)->select('id')->get()->toArray();
        File::whereIn('id',$files)
            ->where('type','img')
            ->update([
                'is_main' => null
            ]);

        File::where([
            'id' => $file->id
        ])->update([
            'is_main' => 1
        ]);
        $content_cats = ContentCat::where('active',1)->get();
        $images = $this->getImages($news->id);
        $files = $this->getDocs($news->id);
        return redirect()->route('admin.news.edit',compact('news','content_cats','files','images'));
    }
    public function doc_set_main(News $news,File $file){
        $files = ContentsFilesMapping::where('content_id',$news->id)->select('id')->get()->toArray();
        File::whereIn('id',$files)
            ->where('type','doc')
            ->update([
                'is_main' => null
            ]);

        File::where([
            'id' => $file->id
        ])->update([
            'is_main' => 1
        ]);
        $content_cats = ContentCat::where('active',1)->get();
        $images = $this->getImages($news->id);
        $files = $this->getDocs($news->id);
        return redirect()->route('admin.news.edit',compact('news','content_cats','files','images'));
    }
    public function ajax_attachment_delete(News $news,File $file){
        File::where('id',$file->id)->delete();
        ContentsFilesMapping::where('file_id',$file->id)->delete();
        \Illuminate\Support\Facades\File::delete(public_path('uploads/news/'.$news->id.'/').'/'.$file->file);
        echo '1';
    }
    public function upload(Request $request){
        foreach ($request->files as $file){
            $f = $file;
        }
        $filename = md5($f->getClientOriginalName());
        $imageName = 'image_'.$filename.'.jpg';
        $avatar_path = $f->move(public_path('uploads/news/in_editor/'), $imageName);
        return response()->json(['location'=> 'uploads/news/in_editor/'.$imageName], 200);
    }

    private function getImages($content_id){
        return ContentsFilesMapping::where('contents_files_mappings.content_id',$content_id)
            ->select([
                'contents_files_mappings.*',
                'files.file as file_name',
                'files.is_main as is_main'
            ])
            ->leftJoin('files','files.id','=','contents_files_mappings.file_id','files')
            ->where('files.type','img')
            ->where('contents_files_mappings.type','news')
            ->get();
    }

    private function getDocs($content_id){
        return ContentsFilesMapping::where('contents_files_mappings.content_id',$content_id)
            ->select([
                'contents_files_mappings.*',
                'files.file as file_name',
                'files.is_main as is_main'
            ])
            ->leftJoin('files','files.id','=','contents_files_mappings.file_id','files')
            ->where('files.type','doc')
            ->where('contents_files_mappings.type','news')
            ->get();
    }

    public function update(Request $request, News $news)
    {

        if(!is_null($request->morefield_arr)){
            $old_morefields = Morefield::pluck('title')->toArray();
            foreach ($request->morefield_arr as $morefield){
                if(!in_array($morefield['title'], $old_morefields)){
                    Morefield::create([
                        'title' => $morefield
                    ]);
                }
            }
        }

        $morefield_maps = MorefieldMapping::where([
            'table_name' => 'news',
            'table_record_id' => $news->id
        ])->get();

        // DELETED ITEMS
        if(!is_null($request->morefield_arr)) {

                if($morefield_maps && isset($request->morefield_arr)) {
                    foreach ($request->morefield_arr as $morefield) {
                        $morefield_record = Morefield::where('title', $morefield['title'])->first();
                        MorefieldMapping::where([
                            'table_name' => 'news',
                            'table_record_id' => $news->id
                        ])
                            ->where('morefield_id','<>',$morefield_record->id)
                            ->delete();
                    }
                }

        }

        if($morefield_maps && isset($request->morefield_arr)){
            $c=0;
            foreach ($request->morefield_arr as $morefield){
                $morefield_record = Morefield::where('title',$morefield['title'])->first();

                MorefieldMapping::updateOrCreate([
                    'table_name' => 'news',
                    'table_record_id' => $news->id,
                    'morefield_id' => $morefield_record->id
                ],[
                    'user_id' => Auth::user()->id,
                    'value' => $morefield['value']
                ]);

                $c++;
            }
        }
        else {
            MorefieldMapping::where([
                'table_name' => 'news',
                'table_record_id' => $news->id
            ])->delete();
        }

        $validate = Validator::make($request->all(), [
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'docs.*' => 'mimes:pdf,doc,docx,mp3,mp4,xls,xlsx',
        ],[
            'images.image' => 'نوع فایل عکس صحیح نیست',
            'images.mimes' => 'فرمت فایل صحیح نیست',
            'docs.mimes' => 'فرمت فایل صحیح نیست',
            'images.max' => 'حجم فایل نمایه بیش از حد مجاز است',
            'image.image' => 'نوع فایل عکس صحیح نیست',
            'image.mimes' => 'فرمت فایل صحیح نیست',
            'image.max' => 'حجم فایل نمایه بیش از حد مجاز است',
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }

        $imageName = $request->old_image;
        if($request->hasFile('image')){
            // delete old image from disk
            if(Storage::exists(public_path('uploads/news/'.$request->image))){
                Storage::delete(public_path('uploads/news/'.$request->image));
            }
            // upload new image
            $filename = md5($request->image->getClientOriginalName());
            $imageName = 'news_'.$filename.'.'.$request->image->extension();
            $avatar_path = $request->image->move(public_path('uploads/news/'), $imageName);
        }

        News::where('id',$news->id)
        ->update([
            'content_cat_id' => $request->content_cat_id,
            'active' => $request->active,
            'title' => $request->title,
            'title_en' => $request->title_en ?? null,
            'brief' => $request->brief ?? null,
            'brief_en' => $request->brief_en ?? null,
            'content' => $request->content ?? null,
            'content_en' => $request->content_en ?? null,
            'name' => $request->name ?? null,
            'share' => $request->share ?? null,
            'image' => $imageName,
            'expire_at' => Melorain::jalali_to_gregorian($request->expire_at) ?? null
        ]);
       if ($request->images){
            foreach($request->images as $file) {

                $filename = md5($file->getClientOriginalName());
                $imageName = 'image_'.$filename.'.'.$file->extension();

                $avatar_path = $file->move(public_path('uploads/news/'.$news->id.'/'), $imageName);
                               // // Create files
                if($avatar_path){
                    $f = File::create([
                        'file' => $imageName,
                        'type' => 'img',
                        'user_id' => Auth::user()->id
                    ]);
                    ContentsFilesMapping::create([
                        'user_id' => Auth::user()->id,
                        'content_id' => $news->id,
                        'file_id' => $f->id,
                        'type' => 'news'
                    ]);
                    $imageName = null;
                }
            }
        }
        if ($request->docs){
            foreach($request->docs as $f) {
                    $filename = md5($f->getClientOriginalName());
                    $fname = $f->getClientOriginalName();
                    $fname_arr = explode('.',$fname);
                    $imageName = 'doc_' . $filename . '.' . end($fname_arr);
                    $avatar_path = $f->move(public_path('uploads/news/' . $news->id . '/'), $imageName);
                    // // Create files
                    if ($avatar_path) {
                        $ff = File::create([
                            'file' => $imageName,
                            'type' => 'doc',
                            'user_id' => Auth::user()->id
                        ]);
                        ContentsFilesMapping::create([
                            'user_id' => Auth::user()->id,
                            'content_id' => $news->id,
                            'file_id' => $ff->id,
                            'type' => 'news'
                        ]);
                        $imageName = null;
                }
            }
        }
        if(!is_null($request->morefield_title)){
            $new_morefields_title = $request->morefield_title;
            $new_morefields_value = $request->morefield_value;
            $old_morefields = Morefield::pluck('title')->toArray();
            $c=0;
            foreach ($new_morefields_title as $morefield){
                if(!in_array($morefield, $old_morefields)){
                    Morefield::create([
                        'title' => $morefield
                    ]);
                }
            }
        }
        $c=0;
        if(!is_null($request->morefield_title)){
            foreach ($new_morefields_title as $morefield){
                $morefield_record = Morefield::where('title',$morefield)->first();
                MorefieldMapping::create([
                    'user_id' => Auth::user()->id,
                    'table_name' => 'news',
                    'table_record_id' => $news->id,
                    'morefield_id' => $morefield_record->id,
                    'value' => $new_morefields_value[$c]
                ]);
                $c++;
            }
        }
        return redirect()->route('admin.news.index')->with('success','رکورد با موفقیت ویرایش شد');
    }

    public function destroy(News $news)
    {
        $news->delete();
        MorefieldMapping::where([
            'table_name' => 'news',
            'table_record_id' => $news->id
        ])->delete();
        return redirect()->route('admin.news.index')->with('success','رکورد با موفقیت حذف شد');
    }
}
