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

Route::get('/', 'ChartController@start');
Route::get('/uusi', 'ChartController@new');
Route::get('/chart/{secretKey}/{playerId?}', 'ChartController@show')->name('chart.show');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// API ROUTES
Route::post('/api/v1/create', 'ChartController@create');
Route::post('/api/v1/save-lane', 'ChartController@saveLane');
Route::post('/api/v1/join', 'ChartController@join');
