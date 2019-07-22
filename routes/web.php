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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::group(['prefix'=>'/','namespace'=>'home'],function(){
    Route::resource('index','IndexController');
    Route::resource('fail','UntreatedController');
    Route::any('index/upload','IndexController@createImg');
});

Route::get('/article','home\ArticleController@index');
Route::get('/article/{id}','home\ArticleController@show');
Route::get('/article/{id}/edit','home\ArticleController@edit');
Route::put('/article/{id}','home\ArticleController@update');
Route::delete('/article/{id}','home\ArticleController@destroy');