<?php

use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\EducationBackgroundController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\WorkExperienceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CpdsController;
use App\Http\Controllers\API\AccountTypeController;
use App\Http\Controllers\API\JobsController;
use App\Http\Controllers\API\CommunicationController;
use App\Http\Controllers\API\UserFcmDeviceTokenController;
use App\Http\Controllers\FlutterwaveWebhookController;
use App\Http\Controllers\API\PaymentController;

//routes for authentication
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('check-phone-number', 'VerifyPhoneNumber');
    Route::post('phone-number-login', 'PhoneLogin');
    Route::post('login-by-google', 'loginByGoogle');
    Route::post('login-by-apple', 'loginByApple');
});
Route::apiResource('account-types', AccountTypeController::class)->only(['index', 'show']);
Route::get('education-background/{userId}', [EducationBackgroundController::class, 'index']);
Route::put('edit-education-background', [EducationBackgroundController::class, 'update']);
Route::post('education-background', [EducationBackgroundController::class, 'store']);
Route::get('work-experience/{userId}', [WorkExperienceController::class, 'index']);
Route::put('edit-work-experience', [WorkExperienceController::class, 'update']);
Route::post('work-experience', [WorkExperienceController::class, 'store']);
Route::post('/webhook/flutterwave', [FlutterwaveWebhookController::class, 'handleWebhook']);
//cpds routes
Route::get('cpds/{userId}', [CpdsController::class, 'index']);
Route::get('upcoming-cpds/{userId}', [CpdsController::class, 'upcoming']);
Route::post('cpds/attend', [CpdsController::class, 'confirm_attendence']);
Route::get('cpds/certificate/{userId}/{cpdId}', [CpdsController::class, 'certificate']);

//events routes
Route::get('upcoming-events/{userId}', [EventController::class, 'upcoming']);
Route::get('attended-events/{id}', [EventController::class, 'attended']);
Route::get('events/{userId}', [EventController::class, 'index']);
Route::post('attend-event', [EventController::class, 'confirm_attendence']);


//jobs routes
Route::apiResource('jobs', JobsController::class)->only(['index', 'show']);

//profile routes
Route::delete('profile/remove/{userId}', [ProfileController::class, 'delete_my_account']);
Route::post('profile/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('profile/verify-password-reset-email', [AuthController::class, 'verifyPasswordResetEmail']);
Route::post('profile/resend-verification-code', [AuthController::class, 'resendVerificationCode']);
Route::post('profile/reset-password-code', [AuthController::class, 'resetPasswordCode']);
Route::post('profile/reset-password', [AuthController::class, 'resetPassword']);

//communication routes
Route::get('communications/{userId}', [CommunicationController::class, 'index']);
Route::post('mark-as-read', [CommunicationController::class, 'markAsRead']);

//fcm device token routes
Route::post('fcm-device-token', [UserFcmDeviceTokenController::class, 'store']);
Route::post('send-notification', [UserFcmDeviceTokenController::class, 'sendNotification']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('update-profile-photo', [ProfileController::class, 'updateProfilePhoto']);
    Route::apiResource('profile', ProfileController::class)->only(['index', 'update', 'show']);
    Route::post('subscribe', [ProfileController::class, 'subscribe']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('membership-certificate', [ProfileController::class, 'generate_membership_certificate']);
    
    Route::get('events/certificate/{event}', [EventController::class, 'generate_certificate']);
    Route::get('cpds/certificate/{event}', [CpdsController::class, 'generate_certificate']);
    Route::get('attended-cpds', [CpdsController::class, 'attended']);
    Route::get('/payments-history', [PaymentController::class, 'flutterWavePayment']);
});

