<?php

namespace App\Http\Controllers;

use App\Models\Version;

class VersionController extends Controller
{

    public function index()
    {
        $versions = Version::orderBy('id','desc')->paginate(20);
        return view('admin.versions.index',compact('versions'));
    }
}
