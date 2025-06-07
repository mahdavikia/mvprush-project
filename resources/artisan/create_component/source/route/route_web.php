/**
 * Created By: Melorain Component Maker 0.1
 * [[date]]
 * Routes for [[component_name]] web component
 **/
Route::get('/[[component_name_single]]/{id}-{title}', [[[component_name_controller]]Controller::class, '[[component_name_single]]'])->name('[[component_name_single]]');
Route::get('{cat_id}/[[component_name]]', [[[component_name_controller]]Controller::class, '[[component_name]]_by_cat_id'])->name('[[component_name]]_by_cat_id');