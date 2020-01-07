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
Route::group(['prefix' => 'cms'], function() {

    Route::group(['prefix' => 'auth'], function() {

        Route::get('/login', 'CMS\LoginController@index')->name('cms.auth.login');
        Route::post('/login', 'CMS\LoginController@doLogin')->name('cms.auth.dologin');

        Route::get('/logout', 'CMS\LoginController@logout')->name('cms.auth.logout');

    });

    Route::group(['middleware' => ['auth:web']], function() {

        Route::get('/dashboard', 'CMS\CMSController@index')->name('cms.dashboard');

	    Route::resource('roles','CMS\RoleController');
	    Route::resource('users','CMS\UserController');

    });

});