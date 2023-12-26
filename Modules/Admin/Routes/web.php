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

        // language route
        Route::match(['get','post'], '/language', 'LanguageController@index');
        Route::match(['get','post'], '/language/add', 'LanguageController@addLanguage');
        Route::match(['get','post'], '/language/edit/{id}', 'LanguageController@editLanguage');
        Route::match(['get','post'], '/language/delete/{id?}', 'LanguageController@destroy');

        // user route
        Route::match(['get','post'], '/user', 'UserController@index');
        Route::match(['get', 'post'], '/user/add', 'UserController@addUser');
        Route::match(['get', 'post'], '/user/edit/{id}', 'UserController@editUser');
        Route::match(['get', 'post'], '/user/delete/{id?}', 'UserController@destroy');

        // get regions on behalf of countries 
        Route::post('/get-regions', 'UserController@getRegions');

        // get countries route
        Route::match(['get','post'], '/countries', 'CountryController@index');

        // update country status route
        Route::post('/update-country-status', 'CountryController@updateCountryStatus');

        // region route
        Route::match(['get','post'], '/region', 'CountryController@region');
        Route::match(['get','post'], '/region/add', 'CountryController@addRegion');
        Route::match(['get','post'], '/region/edit/{id}', 'CountryController@editRegion');
        Route::match(['get','post'], '/region/delete/{id}', 'CountryController@destroy');
        
        // Event route
        Route::match(['get','post'], '/events', 'EventAdminController@index');
        Route::match(['get','post'], '/events/add', 'EventAdminController@addEvents');
        Route::match(['get','post'], '/events/edit/{id}', 'EventAdminController@editEvents');
        Route::match(['get','post'], '/events/delete/{id}', 'EventAdminController@destroy');
        Route::match(['get', 'post'], '/events/deleteImage/{id}', 'EventAdminController@deleteEventImages');

        // News route
        Route::match(['get','post'], '/news', 'NewsAdminController@index');
        Route::match(['get','post'], '/news/add', 'NewsAdminController@addNews');

        // photos
        Route::match(['get','post'], '/photos', 'PhotosController@index');
        Route::match(['get','post'], '/photos/add', 'PhotosController@addPhotos');
        Route::match(['get','post'], '/photos/edit/{id}', 'PhotosController@editPhotos');
        Route::match(['get','post'], '/photos/delete/{id}', 'PhotosController@destroy');
    });
});



