<?php

use Illuminate\Support\Facades\Route;

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


Auth::routes();

Route::get('/tasks', 'TaskController@index')->name('tasks');
Route::post('/task', 'TaskController@store');
Route::delete('/task/{task}', 'TaskController@destroy');


