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
    Route::group([
        'prefix' => 'user',
        'as'     => 'user.',
        'middleware' => 'can:setting'
    ], function () {
        Route::get('normal-setting', 'UserController@normalSetting')->name('normal_setting');
        Route::post('normal-setting', 'UserController@normalSettingUpdate')->name('normal_setting_post');
        Route::post('api-get-session-id', 'UserController@apiGetSessionId')->name('api-get-session-id');
        Route::post('api-get-policy', 'UserController@apiGetPolicy')->name('api-get-policy');
    });
    Route::group([
        'prefix' => 'product',
        'as'     => 'product.',
        'middleware' => 'can:setting'
    ], function () {
        Route::get('post-product', 'ProductController@showPagePostProduct')->name('show-page-post-product');
        Route::post('api-get-item-ebay-info', 'ProductController@apiGetItemEbayInfo')->name('api-get-item-ebay-info');
    });
});
