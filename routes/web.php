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


/**
 * Image routes
 */

Route::get('media/thumbnail/{file}', 'MediaController@thumbnail');
Route::get('media/{file}/{size?}', 'MediaController@resize');

/**
 * Admin Routes
 */

Route::view('admin', 'admin.app');
Route::get('admin/{any}', function () {
	return view('admin.app');
})->where('any', '.*');
