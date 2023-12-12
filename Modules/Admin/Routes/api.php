<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/admin', function (Request $request) {
//     return $request->user();
// });

// registrer user route
Route::post('register_user', 'UserApiController@RegisterUser');

// login user route
Route::post('login_user', 'UserApiController@LoginUser');

Route::group(['middleware'=>['auth:api']], function () {

// logout user route
Route::post('logout_user', 'UserApiController@LogoutUser');

// user Detail route
Route::get('user_detail', 'UserApiController@UserDetail');

// update profile route
Route::post('user_profile_update', 'UserApiController@UserProfileUpdate');
});
