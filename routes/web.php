<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


//if(isset(Auth::user()->id)){
//    Route::get('/', [AdminController::class, 'dashboard'])->name('index');
//} else {
//    Route::get('/', [AdminController::class, 'login'])->name('index');
//}

Route::get('/', [SiteController::class, 'index'])->name('index');