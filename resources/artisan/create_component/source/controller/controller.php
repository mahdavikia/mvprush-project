<?php
/**
* Created By: Melorain Component Maker 0.1
* [[date]]
* [[component_name_controller]]Controller.php
**/
namespace App\Http\Controllers;

use App\Models\[[component_name_controller]];
use App\Models\ContentCat;
use App\Models\ContentsFilesMapping;
use App\Models\File;
use App\Models\Morefield;
use App\Models\MorefieldMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class [[component_name_controller]]Controller extends Controller
{


    public function index(Request $request)
    {
        $filter_title = $request->filter_title ?? null;

        // filter_category storage
        if(!is_null($request->filter_category)){
            $request->session()->put(#*#[[component_name]]_filter_category#*#,$request->filter_category);
        }
        $filter_category = $request->session()->get(#*#[[component_name]]_filter_category#*#) ?? #**#0#**#;// 0= all
        $request->filter_category = $filter_category;

        // filter_active storage
        if(!is_null($request->filter_active) ){
            $request->session()->put(#*#[[component_name]]_filter_active#*#,$request->filter_active);
        }
        $filter_active = $request->session()->get(#*#[[component_name_single]]_filter_active#*#) ?? #**#2#**#;
        $request->filter_active = $filter_active;

        $[[component_name]] = [[component_name_controller]]::orderBy(#*#id#*#,#*#desc#*#)->filters($request)->paginate(10);
        $categories = ContentCat::where(#*#content_cat_type#*#,2)->get();
        return view(#*#admin.[[component_name]].index#*#,compact(#*#[[component_name]]#*#,#*#categories#*#,#*#filter_title#*#,#*#filter_category#*#,#*#filter_active#*#));
    }

    public function create()
    {
        $content_cats = ContentCat::where(#*#active#*#,1)
            ->where(#*#content_cat_type#*#,2)
            ->get();
        $morefields = Morefield::select(#*#title#*#)->get();
        return view(#*#admin.[[component_name]].create#*#,compact(#*#content_cats#*#,#*#morefields#*#));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            #*#image#*# => #*#image|mimes:jpg,png,jpeg,gif,svg|max:2048#*#,
            #*#images.*#*# => #*#image|mimes:jpg,png,jpeg,gif,svg|max:2048#*#,
            #*#docs.*#*# => #*#mimes:pdf,doc,docx,mp3,mp4,xls,xlsx|max:10000#*#,
        ],[
            #*#images.image#*# => #*#نوع فایل عکس صحیح نیست#*#,
            #*#images.mimes#*# => #*#فرمت فایل صحیح نیست#*#,
            #*#docs.mimes#*# => #*#فرمت فایل صحیح نیست#*#,
            #*#images.max#*# => #*#حجم فایل نمایه بیش از حد مجاز است#*#,
            #*#image.image#*# => #*#نوع فایل عکس صحیح نیست#*#,
            #*#image.mimes#*# => #*#فرمت فایل صحیح نیست#*#,
            #*#image.max#*# => #*#حجم فایل نمایه بیش از حد مجاز است#*#,
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        $imageName = null;
        if($request->hasFile(#*#image#*#)){
            $filename = md5($request->image->getClientOriginalName());
            $imageName = #*#[[component_name_single]]_#*#.$filename.#*#.#*#.$request->image->extension();
            $avatar_path = $request->image->move(public_path(#*#uploads/[[component_name]]/#*#), $imageName);
        }

        $[[component_name_single]] = [[component_name_controller]]::create([
                #*#content_cat_id#*# => $request->content_cat_id,
                #*#active#*# => $request->active,
                #*#title#*# => $request->title,
                #*#title_en#*# => $request->title_en,
                #*#brief#*# => $request->brief,
                #*#brief_en#*# => $request->brief_en,
                #*#content#*# => $request->content,
                #*#content_en#*# => $request->content_en,
                #*#name#*# => $request->name,
                #*#user_id#*# => Auth::user()->id,
                #*#image#*# =>  $imageName,
                #*#link#*# =>  $request->link
            ]);
        if ($request->images){
            foreach($request->images as $file) {

                $filename = md5($file->getClientOriginalName());
                $imageName = #*#image_#*#.$filename.#*#.#*#.$file->extension();

                $avatar_path = $file->move(public_path(#*#uploads/[[component_name]]/#*#.$[[component_name_single]]->id.#*#/#*#), $imageName);
                // // Create files
                if($avatar_path){
                    $f = File::create([
                        #*#file#*# => $imageName,
                        #*#type#*# => #*#img#*#,
                        #*#user_id#*# => Auth::user()->id
                    ]);
                    ContentsFilesMapping::create([
                        #*#user_id#*# => Auth::user()->id,
                        #*#content_id#*# => $[[component_name_single]]->id,
                        #*#file_id#*# => $f->id,
                        #*#type#*# => #*#[[component_name_single]]#*#
                    ]);
                    $imageName = null;
                }
            }
        }
        if ($request->docs){
            foreach($request->docs as $f) {
                $filename = md5($f->getClientOriginalName());
                $fname = $f->getClientOriginalName();
                $fname_arr = explode(#*#.#*#,$fname);
                $imageName = #*#doc_#*# . $filename . #*#.#*# . end($fname_arr);
                $avatar_path = $f->move(public_path(#*#uploads/[[component_name]]/#*# . $[[component_name_single]]->id . #*#/#*#), $imageName);
                // // Create files
                if ($avatar_path) {
                    $ff = File::create([
                        #*#file#*# => $imageName,
                        #*#type#*# => #*#doc#*#,
                        #*#user_id#*# => Auth::user()->id
                    ]);
                    ContentsFilesMapping::create([
                        #*#user_id#*# => Auth::user()->id,
                        #*#content_id#*# => $[[component_name_single]]->id,
                        #*#file_id#*# => $ff->id,
                        #*#type#*# => #*#[[component_name_single]]#*#
                    ]);
                    $imageName = null;
                }
            }
        }
        if(!is_null($request->morefield_title)){
            $new_morefields_title = $request->morefield_title;
            $new_morefields_value = $request->morefield_value;
            $old_morefields = Morefield::pluck(#*#title#*#)->toArray();
            $c=0;
            foreach ($new_morefields_title as $morefield){
                if(!in_array($morefield, $old_morefields)){
                    Morefield::create([
                        #*#title#*# => $morefield
                    ]);
                }
            }
        }
        $c=0;
        if(isset($new_morefields_title)){
            foreach ($new_morefields_title as $morefield){
                $morefield_record = Morefield::where(#*#title#*#,$morefield)->first();
                MorefieldMapping::create([
                    #*#user_id#*# => Auth::user()->id,
                    #*#table_name#*# => #*#[[component_name]]#*#,
                    #*#table_record_id#*# => $[[component_name_single]]->id,
                    #*#morefield_id#*# => $morefield_record->id,
                    #*#value#*# => $new_morefields_value[$c]
                ]);
                $c++;
            }
        }
        if(!is_null($request->stay_here)){
            return redirect()->route(#*#admin.[[component_name]].create#*#)->with(#*#success#*#,#*#رکورد با موفقیت ایجاد شد#*#);
        } else {
            return redirect()->route(#*#admin.[[component_name]].index#*#)->with(#*#success#*#,#*#رکورد با موفقیت ایجاد شد#*#);
        }
    }

    public function edit([[component_name_controller]] $[[component_name_single]])
    {
        // content_cat_type : 1 => محتوا
        $content_cats = ContentCat::where(#*#active#*#,1)
            ->where(#*#content_cat_type#*#,2)
            ->get();
        $images = $this->getImages($[[component_name_single]]->id);
        $files = $this->getDocs($[[component_name_single]]->id);

        $morefields = Morefield::select(#*#title#*#)->get();
        $morefields_full = MorefieldMapping::select([
            #*#morefield_mappings.id as id#*#,
            #*#morefield_mappings.value#*#,
            #*#morefields.title as title#*#
        ])->where([
            #*#table_name#*# => #*#[[component_name]]#*#,
            #*#table_record_id#*# => $[[component_name_single]]->id
        ])
            ->leftJoin(#*#morefields#*#,#*#morefields.id#*#,#*#=#*#,#*#morefield_mappings.morefield_id#*#)
            ->get();

        return view(#*#admin.[[component_name]].edit#*#,compact(#*#[[component_name_single]]#*#,#*#content_cats#*#,#*#files#*#,#*#images#*#,#*#morefields#*#,#*#morefields_full#*#));
    }
    public function image_set_main([[component_name_controller]] $[[component_name_single]],File $file){
        $files = ContentsFilesMapping::where(#*#content_id#*#,$[[component_name_single]]->id)->select(#*#id#*#)->get()->toArray();
        File::whereIn(#*#id#*#,$files)
            ->where(#*#type#*#,#*#img#*#)
            ->update([
                #*#is_main#*# => null
            ]);

        File::where([
            #*#id#*# => $file->id
        ])->update([
            #*#is_main#*# => 1
        ]);
        $content_cats = ContentCat::where(#*#active#*#,1)->get();
        $images = $this->getImages($[[component_name_single]]->id);
        $files = $this->getDocs($[[component_name_single]]->id);
        return redirect()->route(#*#admin.[[component_name]].edit#*#,compact(#*#[[component_name_single]]#*#,#*#content_cats#*#,#*#files#*#,#*#images#*#));
    }
    public function doc_set_main([[component_name_controller]] $[[component_name_single]],File $file){
        $files = ContentsFilesMapping::where(#*#content_id#*#,$[[component_name_single]]->id)->select(#*#id#*#)->get()->toArray();
        File::whereIn(#*#id#*#,$files)
            ->where(#*#type#*#,#*#doc#*#)
            ->update([
                #*#is_main#*# => null
            ]);

        File::where([
            #*#id#*# => $file->id
        ])->update([
            #*#is_main#*# => 1
        ]);
        $content_cats = ContentCat::where(#*#active#*#,1)->get();
        $images = $this->getImages($[[component_name_single]]->id);
        $files = $this->getDocs($[[component_name_single]]->id);
        return redirect()->route(#*#admin.[[component_name]].edit#*#,compact(#*#[[component_name_single]]#*#,#*#content_cats#*#,#*#files#*#,#*#images#*#));
    }
    public function ajax_attachment_delete([[component_name_controller]] $[[component_name_single]],File $file){
        File::where(#*#id#*#,$file->id)->delete();
        ContentsFilesMapping::where(#*#file_id#*#,$file->id)->delete();
        \Illuminate\Support\Facades\File::delete(public_path(#*#uploads/[[component_name]]/#*#.$[[component_name_single]]->id.#*#/#*#).#*#/#*#.$file->file);
        echo #*#1#*#;
    }

    private function getImages($content_id){
        return ContentsFilesMapping::where(#*#contents_files_mappings.content_id#*#,$content_id)
            ->select([
                #*#contents_files_mappings.*#*#,
                #*#files.file as file_name#*#,
                #*#files.is_main as is_main#*#
            ])
            ->leftJoin(#*#files#*#,#*#files.id#*#,#*#=#*#,#*#contents_files_mappings.file_id#*#,#*#files#*#)
            ->where(#*#files.type#*#,#*#img#*#)
            ->where(#*#contents_files_mappings.type#*#,#*#[[component_name_single]]#*#)
            ->get();
    }

    private function getDocs($content_id){
        return ContentsFilesMapping::where(#*#contents_files_mappings.content_id#*#,$content_id)
            ->select([
                #*#contents_files_mappings.*#*#,
                #*#files.file as file_name#*#,
                #*#files.is_main as is_main#*#
            ])
            ->leftJoin(#*#files#*#,#*#files.id#*#,#*#=#*#,#*#contents_files_mappings.file_id#*#,#*#files#*#)
            ->where(#*#files.type#*#,#*#doc#*#)
            ->where(#*#contents_files_mappings.type#*#,#*#[[component_name_single]]#*#)
            ->get();
    }

    public function update(Request $request, [[component_name_controller]] $[[component_name_single]])
    {
        if(!is_null($request->morefield_arr)){
            $old_morefields = Morefield::pluck(#*#title#*#)->toArray();
            foreach ($request->morefield_arr as $morefield){
                if(!in_array($morefield[#*#title#*#], $old_morefields)){
                    Morefield::create([
                        #*#title#*# => $morefield
                    ]);
                }
            }
        }

        $morefield_maps = MorefieldMapping::where([
            #*#table_name#*# => #*#[[component_name]]#*#,
            #*#table_record_id#*# => $[[component_name_single]]->id
        ])->get();

        // DELETED ITEMS
        if(!is_null($request->morefield_arr)) {

            if($morefield_maps && isset($request->morefield_arr)) {
                foreach ($request->morefield_arr as $morefield) {
                    $morefield_record = Morefield::where(#*#title#*#, $morefield[#*#title#*#])->first();
                    MorefieldMapping::where([
                        #*#table_name#*# => #*#[[component_name]]#*#,
                        #*#table_record_id#*# => $[[component_name_single]]->id
                    ])
                        ->where(#*#morefield_id#*#,#*#<>#*#,$morefield_record->id)
                        ->delete();
                }
            }

        }

        if($morefield_maps && isset($request->morefield_arr)){
            $c=0;
            foreach ($request->morefield_arr as $morefield){
                $morefield_record = Morefield::where(#*#title#*#,$morefield[#*#title#*#])->first();

                MorefieldMapping::updateOrCreate([
                    #*#table_name#*# => #*#[[component_name]]#*#,
                    #*#table_record_id#*# => $[[component_name_single]]->id,
                    #*#morefield_id#*# => $morefield_record->id
                ],[
                    #*#user_id#*# => Auth::user()->id,
                    #*#value#*# => $morefield[#*#value#*#]
                ]);

                $c++;
            }
        }
        else {
            MorefieldMapping::where([
                #*#table_name#*# => #*#[[component_name]]#*#,
                #*#table_record_id#*# => $[[component_name_single]]->id
            ])->delete();
        }

        $validate = Validator::make($request->all(), [
            #*#image#*# => #*#image|mimes:jpg,png,jpeg,gif,svg|max:2048#*#,
            #*#images.*#*# => #*#image|mimes:jpg,png,jpeg,gif,svg|max:2048#*#,
            #*#docs.*#*# => #*#mimes:pdf,doc,docx,mp3,mp4,xls,xlsx|max:10000#*#,
        ],[
            #*#images.image#*# => #*#نوع فایل عکس صحیح نیست#*#,
            #*#images.mimes#*# => #*#فرمت فایل صحیح نیست#*#,
            #*#docs.mimes#*# => #*#فرمت فایل صحیح نیست#*#,
            #*#images.max#*# => #*#حجم فایل نمایه بیش از حد مجاز است#*#,
            #*#image.image#*# => #*#نوع فایل عکس صحیح نیست#*#,
            #*#image.mimes#*# => #*#فرمت فایل صحیح نیست#*#,
            #*#image.max#*# => #*#حجم فایل نمایه بیش از حد مجاز است#*#,
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        $imageName = $request->old_image;
        if($request->hasFile(#*#image#*#)){
            // delete old image from disk
            if(Storage::exists(public_path(#*#uploads/[[component_name]]/#*#.$request->image))){
                Storage::delete(public_path(#*#uploads/[[component_name]]/#*#.$request->image));
            }
            // upload new image
            $filename = md5($request->image->getClientOriginalName());
            $imageName = #*#[[component_name_single]]_#*#.$filename.#*#.#*#.$request->image->extension();
            $avatar_path = $request->image->move(public_path(#*#uploads/[[component_name]]/#*#), $imageName);
        }

        [[component_name_controller]]::where(#*#id#*#,$[[component_name_single]]->id)
        ->update([
            #*#content_cat_id#*# => $request->content_cat_id,
            #*#active#*# => $request->active,
            #*#title#*# => $request->title,
            #*#title_en#*# => $request->title_en,
            #*#brief#*# => $request->brief,
            #*#brief_en#*# => $request->brief_en,
            #*#content#*# => $request->content,
            #*#content_en#*# => $request->content_en,
            #*#name#*# => $request->name,
            #*#image#*# => $imageName,
            #*#link#*# =>  $request->link
        ]);

        if(!is_null($request->morefield_title)){
            $new_morefields_title = $request->morefield_title;
            $new_morefields_value = $request->morefield_value;
            $old_morefields = Morefield::pluck(#*#title#*#)->toArray();
            $c=0;
            foreach ($new_morefields_title as $morefield){
                if(!in_array($morefield, $old_morefields)){
                    Morefield::create([
                        #*#title#*# => $morefield
                    ]);
                }
            }
        }
        $c=0;
        if(isset($new_morefields_title)){
            foreach ($new_morefields_title as $morefield){
                $morefield_record = Morefield::where(#*#title#*#,$morefield)->first();
                MorefieldMapping::create([
                    #*#user_id#*# => Auth::user()->id,
                    #*#table_name#*# => #*#[[component_name]]#*#,
                    #*#table_record_id#*# => $[[component_name_single]]->id,
                    #*#morefield_id#*# => $morefield_record->id,
                    #*#value#*# => $new_morefields_value[$c]
                ]);
                $c++;
            }
        }

       if ($request->images){
            foreach($request->images as $file) {

                $filename = md5($file->getClientOriginalName());
                $imageName = #*#image_#*#.$filename.#*#.#*#.$file->extension();

                $avatar_path = $file->move(public_path(#*#uploads/[[component_name]]/#*#.$[[component_name_single]]->id.#*#/#*#), $imageName);
                               // // Create files
                if($avatar_path){
                    $f = File::create([
                        #*#file#*# => $imageName,
                        #*#type#*# => #*#img#*#,
                        #*#user_id#*# => Auth::user()->id
                    ]);
                    ContentsFilesMapping::create([
                        #*#user_id#*# => Auth::user()->id,
                        #*#content_id#*# => $[[component_name_single]]->id,
                        #*#file_id#*# => $f->id,
                        #*#type#*# => #*#[[component_name_single]]#*#
                    ]);
                    $imageName = null;
                }
            }
        }
        if ($request->docs){
            foreach($request->docs as $f) {
                    $filename = md5($f->getClientOriginalName());
                    $fname = $f->getClientOriginalName();
                    $fname_arr = explode(#*#.#*#,$fname);
                    $imageName = #*#doc_#*# . $filename . #*#.#*# . end($fname_arr);
                    $avatar_path = $f->move(public_path(#*#uploads/[[component_name]]/#*# . $[[component_name_single]]->id . #*#/#*#), $imageName);
                    // // Create files
                    if ($avatar_path) {
                        $ff = File::create([
                            #*#file#*# => $imageName,
                            #*#type#*# => #*#doc#*#,
                            #*#user_id#*# => Auth::user()->id
                        ]);
                        ContentsFilesMapping::create([
                            #*#user_id#*# => Auth::user()->id,
                            #*#content_id#*# => $[[component_name_single]]->id,
                            #*#file_id#*# => $ff->id,
                            #*#type#*# => #*#[[component_name_single]]#*#
                        ]);
                        $imageName = null;
                }
            }
        }

        if(!is_null($request->stay_here)){
            return redirect()->route(#*#admin.[[component_name]].edit#*#,$[[component_name_single]])->with(#*#success#*#,#*#رکورد با موفقیت ویرایش شد#*#);
        } else {
            return redirect()->route(#*#admin.[[component_name]].index#*#)->with(#*#success#*#,#*#رکورد با موفقیت ویرایش شد#*#);
        }

    }
    public function active(Request $request)
    {
        $u = [[component_name_controller]]::where(#*#id#*#,$request->id)->update([
            #*#active#*# => $request->state
        ]);
        return $u;
    }

    public function destroy([[component_name_controller]] $[[component_name_single]])
    {
        $[[component_name_single]]->delete();
        MorefieldMapping::where([
            #*#table_name#*# => #*#[[component_name]]#*#,
            #*#table_record_id#*# => $[[component_name_single]]->id
        ])->delete();
        return redirect()->route(#*#admin.[[component_name]].index#*#)->with(#*#success#*#,#*#رکورد با موفقیت حذف شد#*#);
    }
}
