<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Unauthorized route
Route::post('/register_user', [AuthController::class, 'RegisterUser']);
Route::post('/login_user', [AuthController::class, 'LoginUser']);

// country listing
Route::get('/country_listing', [CommonController::class, 'getCountry']);

// get regions
Route::post('/get_regions', [CommonController::class, 'getRegions']);

// language listing
Route::get('/language', [CommonController::class, 'getLanguages']);

Route::group(['middleware'=>['auth:api']], function () {
    
    // Authorized route
    Route::post('/logout_user', [AuthController::class, 'LogoutUser']);
    Route::get('/user_detail', [AuthController::class,'UserDetail']);
    Route::post('/user_profile_update', [AuthController::class,'UserProfileUpdate']);
    
    // Add Events
    Route::get('/event_listing/?{lang=locale}', [EventController::class, 'eventListing']);
    Route::post('/event_detail', [EventController::class, 'eventDetail']);
});

