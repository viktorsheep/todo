<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('current_tasks', 'TaskController@index');
Route::get('all_tasks', 'TaskController@all');
Route::get('achived_tasks', 'TaskController@achived');
Route::post('create_task', 'TaskController@store');
Route::post('delete_task/{id}', 'TaskController@destroy');
Route::post('archive_task/{id}', 'TaskController@archive');
Route::post('edit_task', 'TaskController@edit');
Route::post('login', 'AuthController@login');

Route::get('{any}', function () {
    return view('welcome');
})->where('any','.*');

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
