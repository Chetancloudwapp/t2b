<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\OfferApiController;
use App\Http\Controllers\Api\PhotosApiController;
use App\Http\Controllers\Api\InvestmentApiController;

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
    Route::get('/member-listing', [AuthController::class, 'MemberListing']);
    Route::get('/user_detail', [AuthController::class,'UserDetail']);
    Route::post('/user_profile_update', [AuthController::class,'UserProfileUpdate']);
    Route::post('/change_password', [AuthController::class, 'changePassword']);
    Route::delete('/delete_account', [AuthController::class, 'deleteAccount']);

    // Events route
    Route::get('/event_listing', [EventController::class, 'eventListing']);
    Route::get('/event_detail', [EventController::class, 'eventDetail']);
    Route::post('/event_feedback', [EventController::class, 'eventFeedback']);

    // news api route
    Route::get('/news_listing', [NewsApiController::class, 'newsListing']);
    Route::post('/news_detail', [NewsApiController::class, 'newsDetail']);

    // create offer
    Route::post('/create-offer', [OfferApiController::class, 'createOffer']);
    Route::post('/offer-listing', [OfferApiController::class, 'OfferListing']);
    Route::post('/offer-detail', [OfferApiController::class, 'OfferDetails']);

    // photos
    Route::post('/photos-listing', [PhotosApiController::class, 'photosListing']);
    Route::post('/photos-detail', [PhotosApiController::class, 'photosDetail']);

    // investment
    Route::post('/create-investment', [InvestmentApiController::class, 'createInvestment']);
    Route::get('/all-investment-listing', [InvestmentApiController::class, 'allInvestmentListing']);
    Route::get('/all-investment-detail', [InvestmentApiController::class, 'allinvestmentDetail']);
    Route::post('/edit-investment', [InvestmentApiController::class , 'editInvestment']);

});


