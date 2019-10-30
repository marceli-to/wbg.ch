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

// Home
Route::get('/', 'Frontend\HomeController@index')->name('home');

// Projects
Route::get('projekte', 'Frontend\ProjectsController@projects')->name('project.index');
Route::get('projekte/{category}/{slug?}', 'Frontend\ProjectsController@category')->name('project.category');
Route::get('projekte/{category}/{slugCategory?}/{subcategory}/{slugSubcategory?}/{slugProject?}', 'Frontend\ProjectsController@subcategory')->name('project.subcategory');
Route::get('projekt/{id}/{slug?}', 'Frontend\ProjectsController@project')->name('project.detail');

// Profil
Route::get('profil', 'Frontend\ProfileController@index')->name('profile.index');
Route::get('profil/haltung', 'Frontend\ProfileController@attitude')->name('profile.attitude');
Route::get('profil/kompetenzen', 'Frontend\ProfileController@competences')->name('profile.competences');
Route::get('profil/kompetenzen/{slug?}', 'Frontend\ProfileController@competences')->name('profile.competences.detail');
Route::get('profil/kunden', 'Frontend\ProfileController@clients')->name('profile.clients');
Route::get('profil/impressum', 'Frontend\ProfileController@imprint')->name('profile.imprint');

// Team
Route::get('/team', 'Frontend\TeamController@index')->name('team');

// Kontakt
Route::get('/kontakt', 'Frontend\ContactController@index')->name('contact');

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
