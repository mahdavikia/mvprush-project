<?php

namespace App\Providers;

use App\Models\User;
use App\Models\UserOption;
use App\Models\Version;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        App::setLocale('fa');
        // $locale = App::currentLocale();
//        if (App::isLocale('en')) {
//            // ...
//        }


        Schema::defaultStringLength(191);
        View::composer(['admin.layout','admin.dashboard'],function ($view){

            $current_admin = User::with('team')->find(Auth::user()->id);
            $view->with('current_admin',$current_admin);
            $sidebar_theme = UserOption::where('name','sidebar_theme')
                ->where('user_id',Auth::user()->id)
                ->select('value')->first()->value ?? 'sidebar-light';
            $view->with('sidebar_theme',$sidebar_theme);

            $last_version = Version::orderBy('id','desc')->select('name','created_at')->limit(1)->first();
            $view->with('last_version',$last_version);

        });
        Paginator::useBootstrap();

    }
}
