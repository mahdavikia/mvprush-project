<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Morefield;
use App\Models\MorefieldMapping;
use App\Models\Podcast;
use App\Models\ContentCat;
use App\Models\ContentsFilesMapping;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PodcastController extends Controller
{


    public function index(Request $request)
    {
        $filter_title = $request->filter_title ?? null;
//        $filter_category = $request->filter_category ?? 0;// 0= all
//        $filter_active = $request->filter_active ?? 2;// 2 = all

        // filter_content_cat_type storage
        if(!is_null($request->filter_category)){
            $request->session()->put('podcast_filter_category',$request->filter_category);
        } else {
            $request->filter_category = 0;
        }
        $filter_category = $request->session()->get('podcast_filter_category') ?? "0";// 0= all
        $request->filter_category = $filter_category;

        // filter_active storage
        if(!is_null($request->filter_active)){
            $request->session()->put('podcast_filter_active',$request->filter_active);
        } else {
            $request->filter_active = 2;
        }
        $filter_active = $request->session()->get('podcast_filter_active') ?? "2";
        $request->filter_active = $filter_active;

        $podcasts = Podcast::orderBy('id','desc')->filters($request)->paginate(10);
        $categories = ContentCat::where('content_cat_type',3)->get();
        return view('admin.podcasts.index',compact('podcasts','categories','filter_title','filter_category','filter_active'));
    }

    public function create()
    {
        $content_cats = ContentCat::where('active',1)
            ->where('content_cat_type',3)
            ->get();
        $morefields = Morefield::select('title')->get();
        return view('admin.podcasts.create',compact('content_cats','morefields'));
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
            $imageName = 'podcast_'.$filename.'.'.$request->image->extension();
            $avatar_path = $request->image->move(public_path('uploads/podcasts/'), $imageName);
        }
        $podcast = Podcast::create([
                'content_cat_id' => $request->content_cat_id,
                'active' => $request->active,
                'title' => $request->title,
                'title_en' => $request->title_en,
                'brief' => $request->brief,
                'brief_en' => $request->brief_en,
                'content' => $request->content,
                'share' => $request->share,
                'content_en' => $request->content_en,
                'name' => $request->name,
                'user_id' => Auth::user()->id,
                'image' => $imageName
            ]);
        if ($request->images){
            foreach($request->images as $file) {

                $filename = md5($file->getClientOriginalName());
                $imageName = 'image_'.$filename.'.'.$file->extension();

                $avatar_path = $file->move(public_path('uploads/podcasts/'.$podcast->id.'/'), $imageName);
                // // Create files
                if($avatar_path){
                    $f = File::create([
                        'file' => $imageName,
                        'type' => 'img',
                        'user_id' => Auth::user()->id
                    ]);
                    ContentsFilesMapping::create([
                        'user_id' => Auth::user()->id,
                        'content_id' => $podcast->id,
                        'file_id' => $f->id,
                        'type' => 'podcast'
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
                $avatar_path = $f->move(public_path('uploads/podcasts/' . $podcast->id . '/'), $imageName);
                // // Create files
                if ($avatar_path) {
                    $ff = File::create([
                        'file' => $imageName,
                        'type' => 'doc',
                        'user_id' => Auth::user()->id
                    ]);
                    ContentsFilesMapping::create([
                        'user_id' => Auth::user()->id,
                        'content_id' => $podcast->id,
                        'file_id' => $ff->id,
                        'type' => 'podcast'
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
                    'table_name' => 'podcasts',
                    'table_record_id' => $podcast->id,
                    'morefield_id' => $morefield_record->id,
                    'value' => $new_morefields_value[$c]
                ]);
                $c++;
            }
        }
        return redirect()->route('admin.podcasts.index')->with('success','رکورد با موفقیت ایجاد شد');
    }

    public function edit(Podcast $podcast)
    {
        // content_cat_type : 1 => محتوا
        $content_cats = ContentCat::where('active',1)
            ->where('content_cat_type',3)
            ->get();
        $images = $this->getImages($podcast->id);
        $files = $this->getDocs($podcast->id);
        $morefields = Morefield::select('title')->get();
        $morefields_full = MorefieldMapping::select([
            'morefield_mappings.id as id',
            'morefield_mappings.value',
            'morefields.title as title'
        ])->where([
            'table_name' => 'podcasts',
            'table_record_id' => $podcast->id
        ])
            ->leftJoin('morefields','morefields.id','=','morefield_mappings.morefield_id')
            ->get();

        return view('admin.podcasts.edit',compact('podcast','content_cats','files','images','morefields','morefields_full'));
    }
    public function image_set_main(Podcast $podcast,File $file){
        $files = ContentsFilesMapping::where('content_id',$podcast->id)->select('id')->get()->toArray();
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
        $images = $this->getImages($podcast->id);
        $files = $this->getDocs($podcast->id);
        return redirect()->route('admin.podcasts.edit',compact('podcast','content_cats','files','images'));
    }
    public function doc_set_main(Podcast $podcast,File $file){
        $files = ContentsFilesMapping::where('content_id',$podcast->id)->select('id')->get()->toArray();
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
        $images = $this->getImages($podcast->id);
        $files = $this->getDocs($podcast->id);
        return redirect()->route('admin.podcasts.edit',compact('podcast','content_cats','files','images'));
    }
    public function ajax_attachment_delete(podcast $podcast,File $file){
        File::where('id',$file->id)->delete();
        ContentsFilesMapping::where('file_id',$file->id)->delete();
        \Illuminate\Support\Facades\File::delete(public_path('uploads/podcasts/'.$podcast->id.'/').'/'.$file->file);
        echo '1';
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
            ->where('contents_files_mappings.type','podcast')
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
            ->where('contents_files_mappings.type','podcast')
            ->get();
    }

    public function update(Request $request, Podcast $podcast)
    {

        if(!is_null($request->morefield_arr)){
            $old_morefields = Morefield::pluck('title')->toArray();

            foreach ($request->morefield_arr as $morefield){
                if(!in_array($morefield['title'], $old_morefields)){
                    Morefield::create([
                        'title' => $morefield['title']
                    ]);
                }
            }
        }

        $morefield_maps = MorefieldMapping::where([
            'table_name' => 'podcasts',
            'table_record_id' => $podcast->id
        ])->get();

        // DELETED ITEMS
        if(!is_null($request->morefield_arr)) {

            if($morefield_maps && isset($request->morefield_arr)) {
                foreach ($request->morefield_arr as $morefield) {
                    $morefield_record = Morefield::where('title', $morefield['title'])->first();
                    MorefieldMapping::where([
                        'table_name' => 'podcasts',
                        'table_record_id' => $podcast->id
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
                    'table_name' => 'podcasts',
                    'table_record_id' => $podcast->id,
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
                'table_name' => 'podcasts',
                'table_record_id' => $podcast->id
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
            if(Storage::exists(public_path('uploads/podcasts/'.$request->image))){
                Storage::delete(public_path('uploads/podcasts/'.$request->image));
            }
            // upload new image
            $filename = md5($request->image->getClientOriginalName());
            $imageName = 'content_'.$filename.'.'.$request->image->extension();
            $avatar_path = $request->image->move(public_path('uploads/podcasts/'), $imageName);
        }

        Podcast::where('id',$podcast->id)
        ->update([
            'content_cat_id' => $request->content_cat_id,
            'active' => $request->active,
            'title' => $request->title,
            'title_en' => $request->title_en,
            'brief' => $request->brief,
            'brief_en' => $request->brief_en,
            'content' => $request->content,
            'content_en' => $request->content_en,
            'share' => $request->share,
            'name' => $request->name,
            'image' => $imageName
        ]);
       if ($request->images){
            foreach($request->images as $file) {

                $filename = md5($file->getClientOriginalName());
                $imageName = 'image_'.$filename.'.'.$file->extension();

                $avatar_path = $file->move(public_path('uploads/podcasts/'.$podcast->id.'/'), $imageName);
                               // // Create files
                if($avatar_path){
                    $f = File::create([
                        'file' => $imageName,
                        'type' => 'img',
                        'user_id' => Auth::user()->id
                    ]);
                    ContentsFilesMapping::create([
                        'user_id' => Auth::user()->id,
                        'content_id' => $podcast->id,
                        'file_id' => $f->id,
                        'type' => 'podcast'
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
                    $avatar_path = $f->move(public_path('uploads/podcasts/' . $podcast->id . '/'), $imageName);
                    // // Create files
                    if ($avatar_path) {
                        $ff = File::create([
                            'file' => $imageName,
                            'type' => 'doc',
                            'user_id' => Auth::user()->id
                        ]);
                        ContentsFilesMapping::create([
                            'user_id' => Auth::user()->id,
                            'content_id' => $podcast->id,
                            'file_id' => $ff->id,
                            'type' => 'podcast'
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
                    'table_name' => 'podcasts',
                    'table_record_id' => $podcast->id,
                    'morefield_id' => $morefield_record->id,
                    'value' => $new_morefields_value[$c]
                ]);
                $c++;
            }
        }
        return redirect()->route('admin.podcasts.index')->with('success','رکورد با موفقیت ویرایش شد');
    }

    public function site_show($name){
        $podcast = Podcast::where([
            'name' => $name,
            'active' => 1
        ])->first();
        if($podcast){
            $morefields = Podcast::where('name',$name)
                ->select([
                    'morefields.title as title',
                    'morefield_mappings.value as value'
                ])
                ->leftJoin('morefield_mappings', function($join)
                {
                    $join->where('morefield_mappings.table_name','=','podcasts','and');
                    $join->on('morefield_mappings.table_record_id', '=', 'podcasts.id');
                })
                ->leftJoin('morefields','morefields.id','=','morefield_mappings.morefield_id')
                ->orderBy('morefields.id','asc')
                ->get();
        } else {
            return view('errors.404');
        }

        return view('site.podcast_play',compact('podcast','morefields'));
    }

    public function destroy(Podcast $podcast)
    {
        $podcast->delete();
        MorefieldMapping::where([
            'table_name' => 'podcasts',
            'table_record_id' => $podcast->id
        ])->delete();
        return redirect()->route('admin.podcasts.index')->with('success','رکورد با موفقیت حذف شد');
    }
}
