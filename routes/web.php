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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


Route::get('articles/create', 'ArticlesController@create')->middleware('auth')->name('articles.create');

// API
Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {

    Route::get('articles', 'ArticlesController@index')->name('articles.all');

    Route::group(['middleware' => 'auth'], function () {
        Route::post('articles', 'ArticlesController@store')->name('articles.store');
        Route::patch('articles/{article}', 'ArticlesController@update')->name('articles.update');
        Route::delete('articles/{article}', 'ArticlesController@destroy')->name('articles.destroy');
    });
});
