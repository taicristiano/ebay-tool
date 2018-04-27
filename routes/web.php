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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'middleware' => 'auth',
    'namespace'  => 'Admin',
    'as'         => 'admin.',
], function () {
    Route::get('/', 'AdminController@index')->name('index');
    Route::group([
        'prefix' => 'user',
        'as'     => 'user.',
        'middleware' => 'can:user_manager'
    ], function () {
        Route::get('/', 'UserController@index')->name('index');
        Route::get('/create', 'UserController@create')->name('create');
        Route::post('/create', 'UserController@create')->name('create');
        Route::get('/update/{id}', 'UserController@create')->name('update');
        Route::post('/update/{id}', 'UserController@create')->name('update');
        Route::delete('/delete/{id}', 'UserController@delete')->name('delete');
        Route::get('/export/csv', 'UserController@exportCsv')->name('export-csv');
        Route::get('/upload/csv', 'UserController@showPageuploadCsv')->name('show-page-upload-csv');
        Route::post('/upload/csv', 'UserController@uploadCsv')->name('upload-csv');
        Route::any('/fetch', 'UserController@fetch')->name('fetch');
    });
});
