<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkSector\UsersModule\{
    AuthController,
    UserController,
};

Route::prefix('user')->group(function () {
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->middleware("auth:api");
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('forget-password', [AuthController::class, 'forgetPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('resend-verification-token-to-email', [AuthController::class, 'resendVerificationTokenToUserEmail']);
    Route::post('re-check-verification-code', [AuthController::class, 'checkVerificationCodeByEmail']);

    Route::middleware(['auth:api'])->group(function () {
        Route::post('resend-verification-token-to-user', [AuthController::class, 'resendVerificationTokenToAuthenticatedUser']);
        Route::post('check-verification-code', [AuthController::class, 'checkVerificationCodeForAuthenticatedUser']);
        Route::post('change-email', [AuthController::class, 'changeEmail']);
        Route::get('profile', [UserController::class, 'getCurrentUserProfile']);
        Route::put('profile', [UserController::class, 'updateProfile']);
        Route::put('change-password', [UserController::class, 'changePassword']);
        Route::get('details/{user}', [UserController::class, 'getUser']);
        Route::delete("delete-user/{user}",   [UserController::class, 'destroy']);
        Route::get('me', [UserController::class, 'checkAuth']);
        Route::put('update-user-role/{user}', [UserController::class, 'updateUserRole']);
        Route::put('change-account-status/{user}', [UserController::class, 'changeAccountStatus']);

        //        Route::resource('signup-list', UserController::class)->except("index" , "show" , 'create' , 'edit' , 'store');
        //        Route::get('signup-list/{user}/show', [UserController::class , ""]);
    });
});
