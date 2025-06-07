<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Content;
use App\Models\ContentsFilesMapping;
use App\Models\Morefield;
use App\Models\MorefieldMapping;
use App\Models\Podcast;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        //$sliders = $this->getSliders('slider1');
        $sliders = Content::where([
            'content_cat_id' => 8,
            'active' => 1
        ])->get();

        $viewpoint = Content::where([
            'name' => 'viewpoint',
            'active' => 1
        ])->select(['title','brief','id'])->first();

        $viewpoints = Content::where([
            'content_cat_id' => 10,
            'active' => 1
        ])->select(['title','brief','image','id'])->orderBy('id','desc')->take(6)->get();

        $static_book = Content::where([
            'name' => 'static-book',
            'active' => 1
        ])->select(['title','brief','id'])->first();

        $books = Book::where([
            'active' => 1,
            'content_cat_id' => 11
        ])->select(['title','brief','image','id'])->orderBy('id','desc')->take(6)->get();

        $static_seminars = Content::where([
            'name' => 'seminars',
            'active' => 1
        ])->select(['title','brief','id'])->first();

        $last_seminar = Content::where([
            'content_cat_id' => 12,
            'active' => 1
        ])
            ->select(['title','brief','image','start_at','id'])
            ->orderBy('id','desc')
            ->take(1)
            ->first();

        $static_podcast = Content::where([
            'name' => 'static-podcast',
            'active' => 1
        ])->select(['title','brief','id'])->first();

        $podcasts = Podcast::where([
            'active' => 1
        ])
            ->select(['title','brief','image','name','id'])
            ->orderBy('id','desc')
            ->take(3)
            ->get();

        //$morefields = $this->getMoreFields('slider1');

        return view('site.index',compact('sliders','viewpoint','viewpoints','static_book','books','static_seminars','last_seminar','static_podcast','podcasts'));
    }

    public function page($name)
    {

        $page = Content::where([
            'name' => $name,
            'active' => 1
        ])
            ->first();
        if(!$page){
            return view('errors.404');
        }
        return view('site.page',compact('page'));
    }

    public function viewpoint($id = null)
    {
        $viewpoint = Content::where('content_cat_id',10)->where('id',$id)->first();
        $morefields = MorefieldMapping::select([
            'morefield_mappings.id as id',
            'morefield_mappings.value',
            'morefields.title as title'
        ])->where([
            'table_name' => 'contents',
            'table_record_id' => $viewpoint->id
        ])
            ->leftJoin('morefields','morefields.id','=','morefield_mappings.morefield_id')
            ->get();
        return view('site.viewpoint',compact('viewpoint','morefields'));
    }
    public function viewpoints()
    {
        $viewpoints = Content::where('content_cat_id',10)->where('active',1)->paginate(5);
        $static_viewpoints = Content::where([
            'name' => 'viewpoint',
            'active' => 1
        ])->select(['title','brief','id'])->first();

        $viewpoint = Content::where([
            'name' => 'viewpoint',
            'active' => 1
        ])->select(['title','brief','id'])->first();

        return view('site.viewpoints',compact('static_viewpoints','viewpoints','viewpoint'));
    }
    public function podcasts()
    {
        $podcasts = Podcast::where('active',1)->get();
        $static_podcasts = Content::where([
            'name' => 'static-podcast',
            'active' => 1
        ])->select(['title','brief','id'])->first();
        return view('site.podcasts',compact('podcasts','static_podcasts'));
    }

    public function book($id)
    {
        $book = Book::where([
            'active' => 1,
            'id' => $id,
            'content_cat_id' => 11
        ])
            ->first();

        if($book){
                $book->morefields = MorefieldMapping::select([
                    'morefield_mappings.id as id',
                    'morefield_mappings.value',
                    'morefields.title as title'
                ])->where([
                    'table_name' => 'books',
                    'table_record_id' => $book->id
                ])
                    ->leftJoin('morefields','morefields.id','=','morefield_mappings.morefield_id')
                    ->get();
        }

        $static_books = Content::where([
            'name' => 'static-book',
            'active' => 1
        ])->select(['title','brief','id'])->first();
        return view('site.book',compact('book','static_books'));
    }

    public function content($id)
    {
        $content = Content::where([
            'active' => 1,
            'id' => $id
        ])
            ->first();

        if($content){

            $content_more_files = ContentsFilesMapping::where([
                'contents_files_mappings.content_id' => $content->id,
                'contents_files_mappings.type' => 'content',
                'files.type' => 'img'
            ])
                ->leftJoin('files','files.id','=','contents_files_mappings.file_id')
                ->select(['files.file as filename'])
                ->first();

            if($content_more_files){
                $content->more_image = $content_more_files->filename;
            }

            $content->morefields = MorefieldMapping::select([
                'morefield_mappings.id as id',
                'morefield_mappings.value',
                'morefields.title as title'
            ])->where([
                'table_name' => 'contents',
                'table_record_id' => $content->id
            ])
                ->leftJoin('morefields','morefields.id','=','morefield_mappings.morefield_id')
                ->get();

        }

        return view('site.content',compact('content'));
    }

    public function fbook($id)
    {
        $book = Book::where([
            'active' => 1,
            'id' => $id
        ])
            ->first();

        if($book){
            $book->morefields = MorefieldMapping::select([
                'morefield_mappings.id as id',
                'morefield_mappings.value',
                'morefields.title as title'
            ])->where([
                'table_name' => 'books',
                'table_record_id' => $book->id
            ])
                ->leftJoin('morefields','morefields.id','=','morefield_mappings.morefield_id')
                ->get();
        }

        $static_books = Content::where([
            'name' => 'static-book',
            'active' => 1
        ])->select(['title','brief','id'])->first();
        return view('site.fbook',compact('book','static_books'));
    }
    public function books()
    {
        $books = Book::where('active',1)
            ->paginate(5);

        if($books){
            $books->each(function(&$c) {
                $c->morefields = MorefieldMapping::select([
                    'morefield_mappings.id as id',
                    'morefield_mappings.value',
                    'morefields.title as title'
                ])->where([
                    'table_name' => 'books',
                    'table_record_id' => $c->id
                ])
                    ->leftJoin('morefields','morefields.id','=','morefield_mappings.morefield_id')
                    ->get();
            });
        }

        $static_books = Content::where([
            'name' => 'static-book',
            'active' => 1
        ])->select(['title','brief','id'])->first();
        return view('site.books',compact('static_books','books'));
    }
    public function seminar($id)
    {
        $seminar = Content::where('content_cat_id',12)->where('id',$id)->first();
        $morefields = MorefieldMapping::select([
            'morefield_mappings.id as id',
            'morefield_mappings.value',
            'morefields.title as title'
        ])->where([
            'table_name' => 'contents',
            'table_record_id' => $seminar->id
        ])
            ->leftJoin('morefields','morefields.id','=','morefield_mappings.morefield_id')
            ->get();
        return view('site.seminar',compact('seminar','morefields'));
    }
    public function seminars()
    {
        $seminars = Content::where('content_cat_id',12)->where('active',1)->get();
        $static_seminars = Content::where([
            'name' => 'seminars',
            'active' => 1
        ])->select(['title','brief','id'])->first();

        return view('site.seminars',compact('static_seminars','seminars'));
    }

    private function getMoreFields($name){
        $morefields = Content::where('name',$name)
            ->select([
                'morefields.title as title',
                'morefield_mappings.value as value'
            ])
            ->leftJoin('morefield_mappings', function($join)
                {
                    $join->where('morefield_mappings.table_name','=','contents','and');
                    $join->on('morefield_mappings.table_record_id', '=', 'contents.id');
                })
            ->leftJoin('morefields','morefields.id','=','morefield_mappings.morefield_id')
            ->first();
        return $morefields;
    }

    private function getMoreField($name){
        $morefield = Morefield::where('name',$name)->first();
        return $morefield;
    }

    private function getSliders($name){
        $sliders = Content::where('name',$name)
            ->select([
                'contents.id as id',
                'files.file as filename'
            ])
            ->leftJoin('contents_files_mappings','contents_files_mappings.content_id','=','contents.id','files')
            ->leftJoin('files','files.id','=','contents_files_mappings.file_id')
            ->get();
        if($sliders){
            $sliders->each(function(&$c) {
                $c->filename = url('uploads/contents/'.$c->id).'/'.$c->filename;
            });
        }
        return $sliders;
    }
}
