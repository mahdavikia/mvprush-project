<?php

namespace App\Http\Controllers;

use App\Models\UserOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOptionController extends Controller
{
    public function ajax_update(Request $request, $name){
        // check name is exists:
        $e = UserOption::where('name',$name)
            ->where('user_id',Auth::user()->id)
            ->first();
        if($e){
            $u = UserOption::where('name',$name)
                ->where('user_id',Auth::user()->id)
                ->update([
                'value' => $request->value
            ]);
        } else {
            // create name
            $c = UserOption::create([
                'name' => $name,
                'value' => $request->value,
                'user_id' => Auth::user()->id
            ]);
            if($c) $u = 1;
        }
        return $u;
    }
}
