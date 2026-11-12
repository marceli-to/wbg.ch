<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CompetenceController;
use App\Http\Controllers\Admin\CompetenceMediaController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\GridController;
use App\Http\Controllers\Admin\GridElementController;
use App\Http\Controllers\Admin\HomeGridController;
use App\Http\Controllers\Admin\HomeGridElementController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectImageController;
use App\Http\Controllers\Admin\ProjectRelationController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API
|--------------------------------------------------------------------------
|
| Loaded with the "web" middleware group and the /api prefix (see
| bootstrap/app.php): session cookie auth + CSRF (X-XSRF-TOKEN header).
|
| Conventions
|   GET    list            -> JSON array
|   GET    show            -> JSON object
|   POST   store           -> created object (201)
|   PUT    update          -> updated object
|   DELETE destroy         -> 204
|   PATCH  …/{id}/{flag}   -> {"value": 0|1}   (toggles a boolean flag)
|   POST   …/{id}/clone    -> cloned object (201)
|   PUT    …/order         -> 204, body: {"items": [{"id": 1, "order": 0}, …]}
|
*/

Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    // Uploads
    Route::post('uploads/image', [UploadController::class, 'image']);
    Route::post('uploads/document', [UploadController::class, 'document']);

    // Team
    Route::put('team/order', [TeamController::class, 'order']);
    Route::delete('team/media/{filename}', [TeamController::class, 'destroyMedia'])->where('filename', '[A-Za-z0-9._-]+');
    Route::patch('team/{team}/publish', [TeamController::class, 'togglePublish']);
    Route::post('team/{team}/clone', [TeamController::class, 'clone']);
    Route::apiResource('team', TeamController::class)->parameters(['team' => 'team']);

    // Clients
    Route::patch('clients/{client}/publish', [ClientController::class, 'togglePublish']);
    Route::post('clients/{client}/clone', [ClientController::class, 'clone']);
    Route::apiResource('clients', ClientController::class);

    // Categories
    Route::get('categories/subcategories', [CategoryController::class, 'subcategories']);
    Route::put('categories/order', [CategoryController::class, 'order']);
    Route::patch('categories/{category}/publish', [CategoryController::class, 'togglePublish']);
    Route::post('categories/{category}/clone', [CategoryController::class, 'clone']);
    Route::apiResource('categories', CategoryController::class);

    // Competences
    Route::put('competences/order', [CompetenceController::class, 'order']);
    Route::patch('competences/{competence}/publish', [CompetenceController::class, 'togglePublish']);
    Route::post('competences/{competence}/clone', [CompetenceController::class, 'clone']);
    Route::apiResource('competences', CompetenceController::class);
    Route::delete('competence-media/{filename}', [CompetenceMediaController::class, 'destroy'])->where('filename', '[A-Za-z0-9._-]+');
    Route::patch('competence-media/{media}/publish', [CompetenceMediaController::class, 'togglePublish']);

    // Projects
    Route::put('projects/order', [ProjectController::class, 'order']);
    Route::patch('projects/{project}/publish', [ProjectController::class, 'togglePublish']);
    Route::post('projects/{project}/clone', [ProjectController::class, 'clone']);
    Route::apiResource('projects', ProjectController::class);

    // Project relations
    Route::get('projects/{project}/relations', [ProjectRelationController::class, 'index']);
    Route::post('projects/{project}/relations', [ProjectRelationController::class, 'store']);
    Route::delete('project-relations/{relation}', [ProjectRelationController::class, 'destroy']);

    // Project images
    Route::get('projects/{project}/images', [ProjectImageController::class, 'index']);
    Route::put('project-images/order', [ProjectImageController::class, 'order']);
    Route::post('project-images/crop', [ProjectImageController::class, 'crop']);
    Route::delete('project-images/cropped/{filename}', [ProjectImageController::class, 'destroyCropped'])->where('filename', '[A-Za-z0-9._-]+');
    Route::delete('project-images/{filename}', [ProjectImageController::class, 'destroy'])->where('filename', '[A-Za-z0-9._-]+');
    Route::patch('project-images/{image}/{flag}', [ProjectImageController::class, 'toggle'])->whereIn('flag', ['publish', 'is_preview', 'is_preview_related']);

    // Project grids
    Route::get('grid-layouts', [GridController::class, 'layouts']);
    Route::get('projects/{project}/grids', [GridController::class, 'index']);
    Route::post('projects/{project}/grids', [GridController::class, 'store']);
    Route::put('grids/order', [GridController::class, 'order']);
    Route::delete('grids/{grid}', [GridController::class, 'destroy']);
    Route::get('grids/{grid}/elements', [GridElementController::class, 'index']);
    Route::post('grids/{grid}/elements', [GridElementController::class, 'store']);
    Route::delete('grid-elements/{element}', [GridElementController::class, 'destroy']);

    // Home grids
    Route::get('home-grid-layouts', [HomeGridController::class, 'layouts']);
    Route::get('home-grids', [HomeGridController::class, 'index']);
    Route::post('home-grids', [HomeGridController::class, 'store']);
    Route::put('home-grids/order', [HomeGridController::class, 'order']);
    Route::post('home-grids/deploy', [HomeGridController::class, 'deploy']);
    Route::post('home-grids/reset', [HomeGridController::class, 'reset']);
    Route::delete('home-grids/{grid}', [HomeGridController::class, 'destroy']);
    Route::get('home-grids/{grid}/elements', [HomeGridElementController::class, 'index']);
    Route::post('home-grids/{grid}/elements', [HomeGridElementController::class, 'store']);
    Route::delete('home-grid-elements/{element}', [HomeGridElementController::class, 'destroy']);

    // News
    Route::patch('news/{news}/publish', [NewsController::class, 'togglePublish']);
    Route::post('news/{news}/clone', [NewsController::class, 'clone']);
    Route::apiResource('news', NewsController::class)->parameters(['news' => 'news']);

    // Jobs
    Route::put('jobs/order', [JobController::class, 'order']);
    Route::patch('jobs/{job}/publish', [JobController::class, 'togglePublish']);
    Route::post('jobs/{job}/clone', [JobController::class, 'clone']);
    Route::apiResource('jobs', JobController::class);

    // Static content
    Route::get('contents/keys', [ContentController::class, 'keys']);
    Route::patch('contents/{content}/publish', [ContentController::class, 'togglePublish']);
    Route::apiResource('contents', ContentController::class);
});

Route::fallback(fn () => response()->json(['message' => 'Not Found.'], 404));
