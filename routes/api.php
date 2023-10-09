<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Reels\ReelsController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\VerifyUserController;
use App\Http\Controllers\Api\Auth\LoginGoogleController;
use App\Http\Controllers\Api\Auth\LoginFacebookController;
use App\Http\Controllers\Api\Reels\ReelsProfileController;
use App\Http\Controllers\Api\Auth\ResetPasswordUserController;
use App\Http\Controllers\Api\Reels\Comments\CommentsController;
use App\Http\Controllers\Api\UserTypes\FootballUsersController;
use App\Http\Controllers\Api\UserTypes\Pe\FootballPeController;
use App\Http\Controllers\Api\HomePage\FootballUsersTypesController;
use App\Http\Controllers\Api\UserTypes\Coach\FootballCoachController;
use App\Http\Controllers\Api\UserTypes\Player\FootballPlayerController;



Route::prefix('auth')->group(function () {

    Route::post('register',[RegisterController::class,'register']); // finshed
    Route::post('login',[LoginController::class,'login']); // finshed
    // ----------------------------------------------------------------------------------
    Route::post('verify/sendOtp',[VerifyUserController::class,'sendOTP']); // finshed
    Route::post('verify/checkOtp',[VerifyUserController::class,'checkOTP']); // finshed
    Route::post('verify/resendOtp',[VerifyUserController::class,'resendOTP']); // finshed
    // ----------------------------------------------------------------------------------

    Route::post('forgetpassword/sendOtp',[ResetPasswordUserController::class,'sendCode']);
    Route::post('forgetpassword/checkOtp',[ResetPasswordUserController::class,'checkOTP']); // finshed

    // ----------------------------------------------------------------------------------
    Route::post('social/facebook',[LoginFacebookController::class,'login']); // finshed
    Route::post('social/google',[LoginGoogleController::class,'login']); // finshed
});
Route::group(['middleware' => ['auth'],'prefix'=>'auth'],function () {
    Route::post('forgetpassword/resetpassword',[ResetPasswordUserController::class,'resetPassword']); // finshed
    Route::delete('logout',[LoginController::class,'logout']); // finshed
});

Route::group(['middleware' => 'auth','prefix'=>'usertype'],function () {

    Route::get('show',[FootballUsersController::class,'index']);
// ----------------------------------------------------------------------------------------------------------------
    Route::prefix('player')->group(function () {
        Route::post('store', [FootballPlayerController::class, 'storeFootballPlayer'])
        ->middleware('checkprofile'); // finshed
        Route::patch('update', [FootballPlayerController::class, 'updateFootballPlayer'])
        ->middleware('checkplayerprofile'); // finshed
    });
// ----------------------------------------------------------------------------------------------------------------
    Route::prefix('coach')->group(function () {
        Route::post('store', [FootballCoachController::class, 'storeFootballCoach'])
        ->middleware('checkprofile'); // finshed
        Route::patch('update', [FootballCoachController::class, 'updateFootballCoach'])
        ->middleware('checkcouchprofile'); // finshed
    });
// ----------------------------------------------------------------------------------------------------------------
    Route::prefix('pe')->group(function () {
        Route::post('store', [FootballPeController::class, 'storeFootballPe'])
        ->middleware('checkprofile'); // finshed
        Route::patch('update', [FootballPeController::class, 'updateFootballPe'])
        ->middleware('checkpeprofile'); // finshed
    });
});

Route::group(['middleware'=>'auth','prefix'=>'homepage'],function () {
    Route::get('show',[FootballUsersTypesController::class,'index']);
});

Route::group(['middleware'=>'auth','prefix'=>'profile/reels'],function () {
    Route::get('show',[ReelsProfileController::class,'viewProfileReels']); // finshed
    Route::post('store',[ReelsProfileController::class,'storeProfileReels']); // finshed
    Route::patch('update/{id}',[ReelsProfileController::class,'updateProfileReel']); // finshed
    Route::delete('delete/{id}',[ReelsProfileController::class,'deleteProfileReels']); // finshed
    Route::get('arcived',[ReelsProfileController::class,'ArchivedProfileReels']); // finshed
    Route::delete('arcived/delete/{id}',[ReelsProfileController::class,'forceDeleteProfileReels']); // finshed
});


Route::group(['middleware'=>'auth','prefix'=>'reels/{id}'],function () {
    Route::post('view',[ReelsController::class,'viewReel'])->middleware('checkviewreel'); // working
    Route::post('like',[ReelsController::class,'likeReels']); // finshed
    Route::delete('unlike',[ReelsController::class,'unlikeReels']); // finshed
    Route::post('save',[ReelsController::class,'saveReels']); // finshed
    Route::delete('unsave',[ReelsController::class,'unsaveReels']); // finshed
    // Route::post('share',[ReelsController::class,'shareReels']); // working
    Route::post('report',[ReelsController::class,'reportReel']); // working
    Route::get('comments',[ReelsController::class,'viewComentOnReels']); // finshed

});


Route::group(['middleware'=>'auth','prefix'=>'reels/{idreel}/comment'],function () {
    Route::post('store',[CommentsController::class,'storeComment']); // finshed
});

Route::group(['middleware'=>'auth','prefix'=>'reels/{idreel}/comment/{idcomment}'],function () {
    Route::patch('update',[CommentsController::class,'updateComment']); // finshed
    Route::delete('delete',[CommentsController::class,'deleteComment']); // finshed
    Route::post('like',[CommentsController::class,'likeComment']); // finshed
    Route::delete('unlike',[CommentsController::class,'unlikeComment']); //finshed
    Route::post('save',[CommentsController::class,'saveComment']); // finshed
    Route::delete('unsave',[CommentsController::class,'unsaveComment']); // finshed
    Route::post('report',[CommentsController::class,'reportComment']); // finshed
    Route::post('replies',[CommentsController::class,'showReplies']); // finshed
    Route::post('create',[CommentsController::class,'createReplies']); // finshed
});

