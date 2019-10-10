<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function() {
    
    /**
     * Team routes
     */

    Route::get('team/get', 'Backend\Team\TeamController@get');
    Route::post('team/create', 'Backend\Team\TeamController@store');
    Route::get('team/edit/{id}', 'Backend\Team\TeamController@edit');
    Route::post('team/update/{id}', 'Backend\Team\TeamController@update');
    Route::get('team/clone/{id}', 'Backend\Team\TeamController@clone');
    Route::get('team/status/{id}', 'Backend\Team\TeamController@status');
    Route::delete('team/destroy/{id}', 'Backend\Team\TeamController@destroy');
    Route::post('team/order', 'Backend\Team\TeamController@order');
    Route::delete('team/delete/file/{file}', 'Backend\Team\TeamController@unlink');

    /**
     * Client routes
     */

    Route::get('clients/get', 'Backend\Client\ClientController@get');
    Route::post('client/create', 'Backend\Client\ClientController@store');
    Route::get('client/edit/{id}', 'Backend\Client\ClientController@edit');
    Route::post('client/update/{id}', 'Backend\Client\ClientController@update');
    Route::get('client/clone/{id}', 'Backend\Client\ClientController@clone');
    Route::get('client/status/{id}', 'Backend\Client\ClientController@status');
    Route::delete('client/destroy/{id}', 'Backend\Client\ClientController@destroy');

    /**
     * Category routes
     */

    Route::get('categories/get', 'Backend\Category\CategoryController@get');
    Route::post('category/create', 'Backend\Category\CategoryController@store');
    Route::get('category/edit/{id}', 'Backend\Category\CategoryController@edit');
    Route::post('category/update/{id}', 'Backend\Category\CategoryController@update');
    Route::get('category/clone/{id}', 'Backend\Category\CategoryController@clone');
    Route::get('category/status/{id}', 'Backend\Category\CategoryController@status');
    Route::post('category/order', 'Backend\Category\CategoryController@order');
    Route::delete('category/destroy/{id}', 'Backend\Category\CategoryController@destroy');

    /**
     * Competence routes
     */

    Route::get('competences/get', 'Backend\Competence\CompetenceController@get');
    Route::post('competence/create', 'Backend\Competence\CompetenceController@store');
    Route::get('competence/edit/{id}', 'Backend\Competence\CompetenceController@edit');
    Route::post('competence/update/{id}', 'Backend\Competence\CompetenceController@update');
    Route::get('competence/clone/{id}', 'Backend\Competence\CompetenceController@clone');
    Route::get('competence/status/{id}', 'Backend\Competence\CompetenceController@status');
    Route::post('competence/order', 'Backend\Competence\CompetenceController@order');
    Route::delete('competence/destroy/{id}', 'Backend\Competence\CompetenceController@destroy');
    Route::delete('competence/media/delete/{file}', 'Backend\Competence\CompetenceMediaController@unlink');
    Route::get('competence/media/status/{id}', 'Backend\Competence\CompetenceMediaController@status');
 
    /**
     * Project routes
     */
    Route::get('projects/get', 'Backend\Project\ProjectController@all');
    Route::get('projects/fetch/{publish?}/{order?}', 'Backend\Project\ProjectController@fetch');
    Route::get('project/get/{id}', 'Backend\Project\ProjectController@get');
    Route::post('project/create', 'Backend\Project\ProjectController@store');
    Route::get('project/edit/{id}', 'Backend\Project\ProjectController@edit');
    Route::post('project/update/{id}', 'Backend\Project\ProjectController@update');
    Route::get('project/clone/{id}', 'Backend\Project\ProjectController@clone');
    Route::get('project/status/{id}', 'Backend\Project\ProjectController@status');
    Route::post('project/order', 'Backend\Project\ProjectController@order');
    Route::delete('project/destroy/{id}', 'Backend\Project\ProjectController@destroy');
    
    Route::get('project/image/get/{projectId}', 'Backend\Project\ProjectImageController@get');
    Route::delete('project/image/delete/{file}', 'Backend\Project\ProjectImageController@unlink');
    Route::get('project/image/status/{id}', 'Backend\Project\ProjectImageController@status');
    Route::get('project/image/preview/{id}', 'Backend\Project\ProjectImageController@preview');
    Route::post('project/image/order', 'Backend\Project\ProjectImageController@order');
    Route::post('project/image/crop', 'Backend\Project\ProjectImageController@crop');

    Route::get('project/grids/{id}', 'Backend\Project\GridController@get');
    Route::post('project/grids/order', 'Backend\Project\GridController@order');
    Route::get('project/grid/store/{projectId}/{layoutId}', 'Backend\Project\GridController@store');
    Route::delete('project/grid/delete/{id}', 'Backend\Project\GridController@destroy');
    Route::get('project/grid/layouts', 'Backend\Project\GridLayoutController@get');
    Route::get('project/grid/images/{gridId}', 'Backend\Project\GridElementController@get');
    Route::post('project/grid/image/store', 'Backend\Project\GridElementController@store');
    Route::delete('project/grid/image/delete/{id}', 'Backend\Project\GridElementController@destroy');

    /**
     * Home Grid routes
     */
    Route::get('home/grids', 'Backend\Home\HomeGridController@get');
    Route::get('home/grids/deploy', 'Backend\Home\HomeGridController@deploy');
    Route::get('home/grids/reset', 'Backend\Home\HomeGridController@reset');
    Route::get('home/grid/store/{layoutId}', 'Backend\Home\HomeGridController@store');
    Route::delete('home/grid/delete/{id}', 'Backend\Home\HomeGridController@destroy');
    
    Route::get('home/grid/layout/fetch', 'Backend\Home\HomeGridLayoutController@fetch');
    
    Route::post('home/grid/element/store', 'Backend\Home\HomeGridElementController@store');
    Route::delete('home/grid/element/delete/{id}', 'Backend\Home\HomeGridElementController@destroy');
    Route::get('home/grid/element/get/{id}', 'Backend\Home\HomeGridElementController@get');

    /**
     * News routes
     */
    Route::get('news/get', 'Backend\News\NewsController@get');
    Route::post('news/create', 'Backend\News\NewsController@store');
    Route::get('news/edit/{id}', 'Backend\News\NewsController@edit');
    Route::post('news/update/{id}', 'Backend\News\NewsController@update');
    Route::get('news/clone/{id}', 'Backend\News\NewsController@clone');
    Route::get('news/status/{id}', 'Backend\News\NewsController@status');
    Route::delete('news/destroy/{id}', 'Backend\News\NewsController@destroy');

    /**
     * Content routes
     */
    Route::get('contents/get', 'Backend\Content\ContentController@get');
    Route::post('content/create', 'Backend\Content\ContentController@store');
    Route::get('content/edit/{id}', 'Backend\Content\ContentController@edit');
    Route::post('content/update/{id}', 'Backend\Content\ContentController@update');
    Route::get('content/status/{id}', 'Backend\Content\ContentController@status');
    Route::get('content/get/keys', 'Backend\Content\ContentController@keys');

    /**
     * Media routes
     */

    Route::post('media/upload','MediaController@upload');
    Route::post('media/upload/document','MediaController@uploadDocument');
    Route::get('media/{file}/{size?}', 'MediaController@resize');

});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::fallback(function(){
    return response()->json(
        ['message' => 'Page Not Found. If error persists, contact m@marceli.to'],
        404
    );
});

