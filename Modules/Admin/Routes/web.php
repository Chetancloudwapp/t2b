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

Route::prefix('admin')->group(function() {
    // Route::get('/', 'AdminController@index');
    Route::match(['get','post'], '/login', 'AdminController@login');
    
    
    Route::group(['middleware' => ['admin_auth']], function(){
        Route::get('dashboard', 'AdminController@dashboard');
        Route::get('/view_profile', 'AdminController@ViewProfile');
        Route::match(['get','post'], '/edit_profile', 'AdminController@EditProfile');
        Route::match(['get','post'], '/check_current_password', 'AdminController@CheckCurrentPassword');
        Route::match(['get','post'], '/change_password', 'AdminController@ChangePassword');
        Route::get('logout', 'AdminController@logout');
    });
});



