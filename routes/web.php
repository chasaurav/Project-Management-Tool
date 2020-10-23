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

/*
// Had to uncomment this when using Vue Router...
Route::any('{slug}', function () { return view('welcome'); });
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/task', 'HomeController@getTask');
Route::delete('/task/delete/{tasks}', 'HomeController@deleteTask');
Route::post('/task/create', 'HomeController@storeTask');
Route::put('/task/update/{tasks}', 'HomeController@updateTask');

Route::get('/settings', 'HomeController@getSettings');
Route::post('/settings', 'HomeController@storeSettings');

Route::get('/schedule', 'HomeController@getSchedule');
