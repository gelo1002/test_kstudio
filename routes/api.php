<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1'], function () {
    
    // Register/Login by Facebook
    Route::get('login/{drive}',                 'Core\LoginController@redirectToProvider');
    Route::get('login/{drive}/callback',        'Core\LoginController@handleProviderCallback');

    // Register/Login
    Route::post('user/register',                'Core\UserController@create');
    Route::get('user/register/verify-email',    'Core\UserController@verifyEmail');
    Route::post('login',                        'Core\LoginController@login');
});

Route::group(['middleware' => ['auth:api'], 'prefix' => 'v1'], function () {
    // Platform Users
    Route::get('user/profile',                  'Core\UserController@getProfile');
    Route::post('user/profile/files',           'Core\UserController@createFile');
    Route::post('user/profile/all/files',       'Core\UserController@getFiles');
    Route::post('user/profile/file',            'Core\UserController@getFile');
    // Route::put('user/profile/avatar',           'Core\UserController@UpdateAvatar'); // PENDEDING
    Route::put('user/profile',                  'Core\UserController@updateProfile');
    Route::put('user/profile/update-password',  'Core\UserController@updatePassword');
    Route::delete('user/profile/files',         'Core\UserController@deleteFile');
    Route::delete('user/profile',               'Core\UserController@deleteProfile');

    // Admin
    // Route::get('admin/users',                   'Admin\AdminController@create'); // PENDEDING
    // Route::get('admin/user/{eid}',              'Admin\AdminController@create'); // PENDEDING
    Route::post('admin/users/files',            'Admin\AdminController@getFiles'); // PENDEDING
    // Route::post('admin/user/file',              'Admin\UserController@create'); // PENDEDING
    Route::delete('admin/file',                 'Admin\AdminController@deleteFile'); // PENDEDING

    Route::get('logout',  'Core\LoginController@logout');
});