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
        'prefix'     => 'user',
        'as'         => 'user.',
        'middleware' => 'can:user_manager',
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

    // setting shipping route
    Route::group([
        'prefix' => 'shipping',
        'as'     => 'shipping.',
        'middleware' => 'can:setting',
    ], function () {
        Route::get('/', 'ShippingController@index')->name('index');
        Route::get('/create', 'ShippingController@create')->name('create');
        Route::post('/create', 'ShippingController@create')->name('create');
        Route::get('/update/{shippingId}', 'ShippingController@update')->name('update');
        Route::post('/update/{shippingId}', 'ShippingController@update')->name('update');
        Route::delete('/delete/{shippingId}', 'ShippingController@delete')->name('delete');
    });

    // shipping fee
    Route::group([
        'prefix' => 'shipping/{shippingId}/fee',
        'as'     => 'shipping_fee.',
        'middleware' => 'can:setting',
    ], function () {
        Route::get('/', 'ShippingFeeController@index')->name('index');
        Route::get('/create', 'ShippingFeeController@create')->name('create');
        Route::post('/create', 'ShippingFeeController@create')->name('create');
        Route::get('/update/{feeId}', 'ShippingFeeController@create')->name('update');
        Route::post('/update/{feeId}', 'ShippingFeeController@create')->name('update');
        Route::delete('/delete/{feeId}', 'ShippingFeeController@delete')->name('delete');
    });

    Route::group([
        'prefix' => 'user',
        'as'     => 'user.',
        'middleware' => 'can:setting'
    ], function () {
        Route::get('normal-setting', 'SettingNormalController@normalSetting')->name('normal_setting');
        Route::post('normal-setting', 'SettingNormalController@normalSettingUpdate')->name('normal_setting_post');
        Route::post('api-get-session-id', 'SettingNormalController@apiGetSessionId')->name('api-get-session-id');
        Route::post('api-get-policy', 'SettingNormalController@apiGetPolicy')->name('api-get-policy');
    });
    Route::group([
        'prefix' => 'product',
        'as'     => 'product.',
        'middleware' => 'can:setting'
    ], function () {
        Route::get('post', 'ProductController@showPagePostProduct')->name('show-page-post-product');
        Route::get('confirm', 'ProductController@showConfirm')->name('show-confirm');
        Route::post('publish', 'ProductController@postProductPublish')->name('publish');
        Route::post('api-get-item-ebay-info', 'ProductController@apiGetItemEbayInfo')->name('api-get-item-ebay-info');
        Route::post('post-product-confirm', 'ProductController@postProductConfirm')->name('post-product-confirm');
        Route::post('api-get-item-yahoo-or-amazon-info', 'ProductController@apiGetItemYahooOrAmazonInfo')->name('api-get-item-yahoo-or-amazon-info');
        Route::post('calculator-profit', 'ProductController@calculatorProfit')->name('calculator-profit');
        Route::post('update-profit', 'ProductController@updateProfit')->name('update-profit');
        Route::get('get-image-init', 'ProductController@getImageInit')->name('get-image-init');
    });

    // setting template
    Route::group([
        'prefix' => 'template',
        'as'     => 'template.',
        'middleware' => 'can:setting',
    ], function () {
        Route::get('/', 'TemplateController@index')->name('index');
        Route::get('/create', 'TemplateController@create')->name('create');
        Route::post('/create', 'TemplateController@create')->name('create');
        Route::get('/update/{templateId}', 'TemplateController@create')->name('update');
        Route::post('/update/{templateId}', 'TemplateController@create')->name('update');
        Route::delete('/delete/{templateId}', 'TemplateController@delete')->name('delete');
    });
});
