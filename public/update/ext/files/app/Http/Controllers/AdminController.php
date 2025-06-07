<?php
/**
* UPDATED
*/
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Contact;
use App\Models\Content;
use App\Models\News;
use App\Models\Podcast;
use App\Models\Shortcut;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function index()
    {
        if(!Auth::check()) {
            return redirect()->route('admin.login');
        }
        return view('admin.index');
    }

    public function getAverage($model){
        $a = $model->toArray();
        $a = array_filter($a);
        $aa = [];
        foreach ($a as $a_count){
            array_push($aa,$a_count['count']);
        }
        if(array_sum($aa) > 0){
            $average = array_sum($aa)/count($aa);
        } else {
            $average = 0;
        }
        return $average;
    }

    public function dashboard()
    {

        // BOOKS STATICS
        $counts_books_per_date = Book::select(
            DB::raw('DATE(created_at) AS date'),
            DB::raw('COUNT(id) AS count'),
        )
            ->where('created_at', '>=', Carbon::now()->subYears(30))
            ->groupBy(
                DB::raw('DATE(created_at)')
            )
            ->orderBy(
                DB::raw('DATE(created_at)')
            )->get();
        $book_statics = [];
        $d_arr=[];
        $c_arr=[];
        if($counts_books_per_date){

            foreach ($counts_books_per_date as $row){
                $jdate = \Melorain::gregorian_to_jalali($row->date,false);
                array_push($d_arr,$jdate);
                array_push($c_arr,$row->count);
            }
            $book_statics['dates'] = '"'.implode('","', $d_arr).'"';
            $book_statics['counts'] = implode(',',$c_arr);
        }

        $book_statics['title'] = 'در یک سال اخیر';

        // NEWS STATICS
        $counts_news_per_date = News::select(
            DB::raw('DATE(created_at) AS date'),
            DB::raw('COUNT(id) AS count'),
        )
            ->where('created_at', '>=', Carbon::now()->subMonth(3))
            ->groupBy(
                DB::raw('DATE(created_at)')
            )
            ->orderBy(
                DB::raw('DATE(created_at)')
            )->get();
        $news_statics = [];
        $d_arr=[];
        $c_arr=[];
        if($counts_news_per_date){

            foreach ($counts_news_per_date as $row){
                $jdate = \Melorain::gregorian_to_jalali($row->date,false);
                array_push($d_arr,$jdate);
                array_push($c_arr,$row->count);
            }
            $news_statics['dates'] = '"'.implode('","', $d_arr).'"';
            $news_statics['counts'] = implode(',',$c_arr);
        }
        $news_statics['title'] = 'در سه ماه اخیر';

        // PODCAST STATICS
        $counts_podcast_per_date = Podcast::select(
            DB::raw('DATE(created_at) AS date'),
            DB::raw('COUNT(id) AS count'),
        )
            ->where('created_at', '>=', Carbon::now()->subMonth(6))
            ->groupBy(
                DB::raw('DATE(created_at)')
            )
            ->orderBy(
                DB::raw('DATE(created_at)')
            )->get();
        $podcast_statics = [];
        $d_arr=[];
        $c_arr=[];
        if($counts_podcast_per_date){

            foreach ($counts_podcast_per_date as $row){
                $jdate = \Melorain::gregorian_to_jalali($row->date,false);
                array_push($d_arr,$jdate);
                array_push($c_arr,$row->count);
            }
            $podcast_statics['dates'] = '"'.implode('","', $d_arr).'"';
            $podcast_statics['counts'] = implode(',',$c_arr);
        }
        $podcast_statics['title'] = 'در شش ماه اخیر';

        // CONTACTS STATICS
        $counts_contact_per_date = Contact::select(
            DB::raw('DATE(created_at) AS date'),
            DB::raw('COUNT(id) AS count'),
        )
            ->where('created_at', '>=', Carbon::now()->subYear(1))
            ->groupBy(
                DB::raw('DATE(created_at)')
            )
            ->orderBy(
                DB::raw('DATE(created_at)')
            )->get();
        $contact_statics = [];
        $d_arr=[];
        $c_arr=[];
        if($counts_contact_per_date){

            foreach ($counts_contact_per_date as $row){
                $jdate = \Melorain::gregorian_to_jalali($row->date,false);
                array_push($d_arr,$jdate);
                array_push($c_arr,$row->count);
            }
            $contact_statics['dates'] = '"'.implode('","', $d_arr).'"';
            $contact_statics['counts'] = implode(',',$c_arr);
        }
        $contact_statics['title'] = 'در یک سال اخیر';

        $shortcuts = Shortcut::where([
            'active' => 1
        ])->orderBy('sort_order','asc')->orderBy('sort_order','asc')->get();

        return view('admin.dashboard',compact('book_statics','news_statics','podcast_statics','contact_statics','shortcuts'));
    }

    public function search(Request $request)
    {
        $news = News::filters($request)->get();
        $contents = Content::filters($request)->get();
        $podcasts = Podcast::filters($request)->get();
        $books = Book::filters($request)->get();

        $filter_title = $request->filter_title;

        return view('admin.search',compact('news','contents','podcasts','books','filter_title'));
    }

    public function login() {
        return view('admin.login');
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
