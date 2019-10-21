<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

/**
 * Page routes
 */

Route::get('/', 'Frontend\HomeController@index')->name('home');

Route::get('projekte', 'Frontend\ProjectsController@projects')->name('project.index');
Route::get('projekt/{id}/{slug?}', 'Frontend\ProjectsController@project')->name('project.detail');

/**
 * Image routes
 */

Route::middleware('cache.headers:public;max_age=2628000;etag')->group(function() {
	Route::get('media/thumbnail/{file}', 'MediaController@thumbnail');
	Route::get('media/source/{file}', 'MediaController@source');
	Route::get('media/preview/{file}', 'MediaController@preview');
	Route::get('media/{file}/{size?}', 'MediaController@resize');
});

/**
 * Admin Routes
 */

Route::view('admin', 'admin.app');
Route::get('admin/{any}', function () {
	return view('admin.app');
})->where('any', '.*');
