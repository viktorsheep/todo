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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');
        Route::get('refresh', 'AuthController@refresh');

        
        // Below mention routes are available only for the authenticated users.
        Route::middleware('auth:api')->group(function () {
            // Get user info
            Route::get('user', 'AuthController@user');
            // Logout user from application
            Route::post('logout', 'AuthController@logout');
        });
    });
});

Route::get('/', function () {
    return view('main');
});

Route::get('current_tasks', 'TaskController@index');
Route::get('all_tasks', 'TaskController@all');
Route::get('achived_tasks', 'TaskController@achived');
Route::post('create_task', 'TaskController@store');
Route::post('delete_task/{id}', 'TaskController@destroy');
Route::post('archive_task/{id}', 'TaskController@archive');
Route::post('edit_task', 'TaskController@edit');