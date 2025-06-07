<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContentCatController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserOptionController;
use App\Http\Controllers\VersionController;
use App\Models\Team;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('admin', [AdminController::class, 'dashboard'])->name('dashboard')->middleware('authGuard');

Route::get('admin/login', function () {
    $teams = Team::get();
    return view('admin.login',compact('teams'));
})->name('admin.login');

Route::post('admin/login-post', [CustomAuthController::class, 'login_post'])->name('admin.login.post');
Route::get('admin/logout', [CustomAuthController::class, 'logout'])->name('admin.logout');
Route::get('admin/reload-captcha', [ContactController::class, 'reloadCaptcha']);

Route::name('admin.')
    ->prefix('admin')
    ->middleware('authGuard')
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('index');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/search', [AdminController::class, 'search'])->name('search');
});
/**
 *  USERS
 */
Route::name('admin.users.')
    ->prefix('/admin/users')
    ->middleware('authGuard')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/show/{user}', [UserController::class, 'show'])->name('show');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
        Route::post('/active', [UserController::class, 'active'])->name('active');
    });
/**
 *  ROLES
 */
Route::name('admin.roles.')
    ->prefix('/admin/roles')
    ->middleware('authGuard')
    ->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/show/{role}', [RoleController::class, 'show'])->name('show');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/destroy/{role}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{role}', [RoleController::class, 'edit'])->name('edit');
        Route::post('/active', [RoleController::class, 'active'])->name('active');
    });
/**
 *  PERMISSIONS
 */
Route::name('admin.permissions.')
    ->prefix('/admin/permissions')
    ->middleware('authGuard')
    ->group(function () {
        Route::get('/{role}', [PermissionController::class, 'index'])->name('index');
        Route::get('/{role}/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/{role}/store', [PermissionController::class, 'store'])->name('store');
        Route::get('/{role}/show/{permission}', [PermissionController::class, 'show'])->name('show');
        Route::put('/{role}/{permission}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{role}/destroy/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::get('/{role}/edit/{permission}', [PermissionController::class, 'edit'])->name('edit');
        Route::get('/{role}/createAll', [PermissionController::class, 'createAll'])->name('createAll');
        Route::post('/{role}/storeAll', [PermissionController::class, 'storeAll'])->name('storeAll');
});
/**
 *  PARAMETERS
 */
Route::name('admin.parameters.')
    ->prefix('/admin/parameters')
    ->middleware('authGuard')
    ->group(function () {
        Route::get('/', [ParameterController::class, 'index'])->name('index');
        Route::get('/create', [ParameterController::class, 'create'])->name('create');
        Route::post('/store', [ParameterController::class, 'store'])->name('store');
        Route::get('/show/{parameter}', [ParameterController::class, 'show'])->name('show');
        Route::put('/{parameter}', [ParameterController::class, 'update'])->name('update');
        Route::delete('/destroy/{parameter}', [ParameterController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{parameter}', [ParameterController::class, 'edit'])->name('edit');
    });

/**
 *  USER_OPTIONS
 */
Route::name('admin.user_options.')
    ->prefix('/admin/user-options')
    ->middleware('authGuard')
    ->group(function () {
        Route::post('/ajax_update/{name}', [userOptionController::class, 'ajax_update'])->name('ajax_update');
    });
/**
 *  CONTENT_CATS
 */
Route::name('admin.content_cats.')
    ->prefix('/admin/content_cats')
    ->middleware('authGuard')
    ->group(function () {
        Route::get('/', [ContentCatController::class, 'index'])->name('index');
        Route::post('/', [ContentCatController::class, 'index'])->name('index');
        Route::get('/create', [ContentCatController::class, 'create'])->name('create');
        Route::post('/store', [ContentCatController::class, 'store'])->name('store');
        Route::get('/show/{content_cat}', [ContentCatController::class, 'show'])->name('show');
        Route::put('/{content_cat}', [ContentCatController::class, 'update'])->name('update');
        Route::delete('/destroy/{content_cat}', [ContentCatController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{content_cat}', [ContentCatController::class, 'edit'])->name('edit');
        Route::post('/active', [ContentCatController::class, 'active'])->name('active');
    });
/**
 *  SETTINGS
 */
Route::name('admin.settings.')
    ->prefix('/admin/setting')
    ->middleware('authGuard')
    ->group(function () {
        Route::get('/index', [SettingController::class, 'index'])->name('index');
        Route::post('/index', [SettingController::class, 'index'])->name('index');
        Route::get('/create', [SettingController::class, 'create'])->name('create');
        Route::post('/store', [SettingController::class, 'store'])->name('store');
        Route::get('/show/{content}', [SettingController::class, 'show'])->name('show');
        Route::put('/{setting}', [SettingController::class, 'update'])->name('update');
        Route::delete('/destroy/{setting}', [SettingController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{setting}', [SettingController::class, 'edit'])->name('edit');
        Route::post('/attachment/delete/{setting}/{file}', [SettingController::class, 'ajax_attachment_delete'])->name('ajax_attachment_delete');
        Route::get('/image/set-main/{setting}/{file}', [SettingController::class, 'image_set_main'])->name('image_set_main');
        Route::get('/doc/set-main/{setting}/{file}', [SettingController::class, 'doc_set_main'])->name('doc_set_main');
        Route::post('/active', [SettingController::class, 'active'])->name('active');
    });
/**
 *  VERSIONS
 */
Route::name('admin.versions.')
    ->prefix('/admin/versions')
    ->middleware('authGuard')
    ->group(function () {
        Route::get('/', [VersionController::class, 'index'])->name('index');
    });