/**
 * Created By: Melorain Component Maker 0.1
 * [[date]]
 * Routes for [[component_name]] admin component
 **/
Route::name('admin.[[component_name]].')
    ->prefix('/admin/[[component_name]]')
    ->middleware('authGuard')
    ->group(function () {
        Route::get('/index', [[[component_name_controller]]Controller::class, 'index'])->name('index');
        Route::post('/index', [[[component_name_controller]]Controller::class, 'index'])->name('index');
        Route::get('/create', [[[component_name_controller]]Controller::class, 'create'])->name('create');
        Route::post('/store', [[[component_name_controller]]Controller::class, 'store'])->name('store');
        Route::get('/show/{[[component_name_single]]}', [[[component_name_controller]]Controller::class, 'show'])->name('show');
        Route::put('/{[[component_name_single]]}', [[[component_name_controller]]Controller::class, 'update'])->name('update');
        Route::delete('/destroy/{[[component_name_single]]}', [[[component_name_controller]]Controller::class, 'destroy'])->name('destroy');
        Route::get('/edit/{[[component_name_single]]}', [[[component_name_controller]]Controller::class, 'edit'])->name('edit');
        Route::post('/attachment/delete/{[[component_name_single]]}/{file}', [[[component_name_controller]]Controller::class, 'ajax_attachment_delete'])->name('ajax_attachment_delete');
        Route::get('/image/set-main/{[[component_name_single]]}/{file}', [[[component_name_controller]]Controller::class, 'image_set_main'])->name('image_set_main');
        Route::get('/doc/set-main/{[[component_name_single]]}/{file}', [[[component_name_controller]]Controller::class, 'doc_set_main'])->name('doc_set_main');
        Route::post('/active', [[[component_name_controller]]Controller::class, 'active'])->name('active');
    });