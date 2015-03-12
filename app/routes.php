<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'NewsController@getIndex');

// CSRF
Route::when('*', 'csrf', array('post', 'put', 'delete'));

// Hírek
Route::get('hir/{id}/{title}', 'NewsController@getShowNews');
Route::controller('hirek', 'NewsController');

// Rólunk
Route::controller('rolunk', 'PageController');

// Galéria
Route::get('album/{id}/{title}', 'AlbumController@getShowAlbum');
Route::controller('albumok', 'AlbumController');

// Rekordok
Route::controller('rekordok', 'RecordController');

// Felhasználó
Route::controller('felhasznalo', 'AccountController');

Route::get('/logout', function() {
    Session::forget('user_full_name');
    Session::forget('admin');
    Session::forget('member');
    Auth::logout();
    return Redirect::to('/');
});