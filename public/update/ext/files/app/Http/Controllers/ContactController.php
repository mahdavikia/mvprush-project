<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> captcha_img('math')]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(),200);
        }
        $clientIP = $request->ip();

        $contact = Contact::create([
            'ip'=> $clientIP,
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'content' => $request->content,
            'read_state' => 0
        ]);
        return 1;
    }

    public function index(Request $request)
    {
        $keyword = $request->keyword ?? null;

        // filter_active storage
        if(!is_null($request->filter_read_state)){
            $request->session()->put('contact_filter_read_state',$request->filter_read_state);
        }
        $filter_read_state = $request->session()->get('contact_filter_read_state') ?? "2";
        $request->filter_read_state = $filter_read_state;

        $contacts = Contact::orderBy('id','desc')
            ->search($request->keyword)
            ->filters($request)
            ->filterDate($request)
            ->paginate(20);

        return view('admin.contacts.index',compact('contacts','keyword','filter_read_state'));
    }

    public function read_state(Request $request){
        if(!is_null($request->state)) {
            $res = Contact::where('id', $request->id)->update(['read_state' => $request->state]);
            if ($res) {
                return 1;
                //return $this->successResponse(null, 200, 'وضعیت خوانده شده ثبت شد');
            } else {
                return 0;
                //return $this->errorResponse('خطا در تغییر وضعیت: شناسه معتبر نیست', 200);
            }
        }
    }
    public function get_unread_messages_count(){
        $unread_messages = Contact::where('read_state',0)->count();
        return $unread_messages;
    }
    public function site_create(Request $request){
        return view('site.contact');
    }
    public function site_create_post(Request $request){

        $validate = Validator::make($request->all(), [
            'fullname' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'content' => 'required',
            'captcha' => 'required|captcha'
        ],[
            'fullname.required' => 'نام و نام خانوادگی اجباریست',
            'email.required' => 'وارد کردن ایمیل اجباریست',
            'email.email' => 'فرمت ایمیل صحیح نیست',
            'subject.required' => 'وارد کردن موضوع اجباریست',
            'content.required' => 'متن پیغام وارد نشده است',
            'captcha.required' => 'کد امنیتی را وارد کنید',
            'captcha.captcha' => 'کد امنیتی صحیح نیست',
        ]);
        if ($validate->fails()) {
            return back()->withErrors($validate->errors())->withInput();
        } else {
            $clientIP = $request->ip();
            $contact = Contact::create([
                'ip'=> $clientIP,
                'name' => $request->fullname,
                'email' => $request->email,
                'subject' => $request->subject,
                'content' => $request->content,
                'read_state' => 0
            ]);
            return back()->with('success','پیغام شما با موفقیت ارسال شد');
        }
    }
}
