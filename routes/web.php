<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ProjectsController;
use App\Http\Controllers\Frontend\TeamController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pages
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Projects
Route::get('projekte', [ProjectsController::class, 'projects'])->name('project.index');
Route::get('projekte/{category}/{slug?}', [ProjectsController::class, 'category'])->name('project.category')->whereNumber('category');
Route::get('projekte/{category}/{slugCategory?}/{subcategory}/{slugSubcategory?}/{slugProject?}', [ProjectsController::class, 'subcategory'])->name('project.subcategory')->whereNumber(['category', 'subcategory']);
Route::get('projekt/{id}/{slug?}/{anchor?}', [ProjectsController::class, 'project'])->name('project.detail')->whereNumber('id');

// Profile
Route::get('profil/haltung', [ProfileController::class, 'attitude'])->name('profile.attitude');
Route::get('profil/kompetenzen/{slug?}', [ProfileController::class, 'competences'])->name('profile.competences');
Route::get('profil/kunden', [ProfileController::class, 'clients'])->name('profile.clients');
Route::get('profil/rechtliches', [ProfileController::class, 'legal'])->name('profile.legal');
Route::get('agb', [ProfileController::class, 'toc'])->name('toc');

// Team & contact
Route::get('team', [TeamController::class, 'index'])->name('team');
Route::get('kontakt', [ContactController::class, 'index'])->name('contact');

/*
|--------------------------------------------------------------------------
| Images
|--------------------------------------------------------------------------
|
| Fallback routes that generate a processed image on first request. Once
| generated, ImageHelper points to the static file in /storage instead.
|
*/

Route::middleware('cache.headers:public;max_age=2628000;etag')
    ->where(['file' => '[A-Za-z0-9._-]+'])
    ->group(function () {
        Route::get('media/thumbnail/{file}', [MediaController::class, 'thumbnail']);
        Route::get('media/source/{file}', [MediaController::class, 'source']);
        Route::get('media/preview/{file}', [MediaController::class, 'preview']);
        Route::get('media/related/{file}', [MediaController::class, 'related']);
        Route::get('media/{file}/{size?}', [MediaController::class, 'resize'])->whereIn('size', ['xs', 'sm', 'md', 'lg']);
    });

/*
|--------------------------------------------------------------------------
| Admin (Vue SPA)
|--------------------------------------------------------------------------
*/

Route::view('admin/{any?}', 'admin.app')->where('any', '.*');
