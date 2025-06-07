<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index()
    {
        if(!Auth::check()) {
            return redirect()->route('admin.login');
        }
        return view('admin.index');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function search(Request $request)
    {
        return view('admin.search');
    }

    public function login() {
        $teams = Team::get();
        return view('admin.login',compact('teams'));
    }
}
