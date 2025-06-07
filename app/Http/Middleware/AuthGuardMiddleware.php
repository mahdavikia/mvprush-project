<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthGuardMiddleware
{

    public function handle(Request $request, Closure $next)
    {

        if(!Auth::check()){
            return redirect()->route('admin.login');
        }


        $currentAction = \Route::currentRouteAction();

        if(str_contains($currentAction, '@')) {
            list($controller, $method) = explode('@', $currentAction);
            $controller = preg_replace('/.*\\\/', '', $controller);
            $fullControllerName = $controller . '@' . $method;
        }else{
            $fullControllerName = $currentAction;
        }
        // CHECK PERMISSION HERE
        $user_id = Auth::user()->id;
        $current_user_role = User::find($user_id);
        $current_user_permissions = Permission::where(['role_id' => $current_user_role->role->id])->get();

        foreach($current_user_permissions as $per){
            if($per->route == $fullControllerName || $per->route == $controller.'@full' || $fullControllerName == null || $fullControllerName == 'AdminController@search'){
                return $next($request);
            }
        }
        return response()->view('admin.access_denied', [], 201);
    }
}
