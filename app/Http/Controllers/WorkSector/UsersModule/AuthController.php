<?php

namespace App\Http\Controllers\WorkSector\UsersModule;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\WorkSector\UsersModule\User;
use App\Services\GeneralServices\EmailVerifyService;
use App\Http\Resources\WorkSector\UsersModule\UserResource;
use App\Http\Requests\WorkSector\UsersModule\RegisterRequest;
use App\Services\WorkSector\UsersModule\UserEmailChangerService;
use App\Services\WorkSector\UsersModule\LoginService\LoginService;
use App\Http\Requests\WorkSector\UsersModule\ForgetPasswordRequest;
use App\Services\WorkSector\UsersModule\UserCreatingService\UserCreatingService;
use App\Services\WorkSector\UsersModule\ResetPasswordService\ResetPasswordService;
use App\Services\WorkSector\UsersModule\EmailVerificationService\EmailVerificationService;
use App\Services\WorkSector\UsersModule\EmailVerificationService\VerificationNotificationSender;
use App\Services\WorkSector\UsersModule\ResetPasswordService\SendResetPasswordNotificationService;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // return response()->json(auth()->user());
        return (new LoginService())->login($request);
    }

    public function register(RegisterRequest  $request): JsonResponse
    {
        return (new UserCreatingService())->create($request);
    }

    //During the user creation process a verification token will be sent to user's email
    //But This route is used to RESEND a new verification token
    public function resendVerificationTokenToAuthenticatedUser(Request $request): JsonResponse
    {
        return (new VerificationNotificationSender())->setUser(auth("api")->user())->send();
    }

    //During the user creation process a verification token will be sent to user's email
    //But This route is used to RESEND a new verification token
    public function resendVerificationTokenToUserEmail(Request $request): JsonResponse
    {
        return (new VerificationNotificationSender())->send($request);
    }

    public function verifyEmail(Request $request)
    {
        // return response()->json($request);
        return (new EmailVerifyService())->checkEmail($request, new User());
    }
    //This route is used to verify the email of the authenticated user
    public function checkVerificationCodeForAuthenticatedUser(Request $request): JsonResponse
    {
        return (new EmailVerificationService())->setUser(auth("api")->user())->verify($request);
    }

    //This route is used to verify the given email (if the given token is correct)
    public function checkVerificationCodeByEmail(Request $request): JsonResponse
    {
        return (new EmailVerificationService())->verify($request);
    }

    public function changeEmail(Request $request): JsonResponse
    {
        return (new UserEmailChangerService(auth("api")->user()))->change($request);
    }


    public function logout(): JsonResponse
    {
        auth()->logout();
        return Response::success([], ["logout success"], 200);
    }

    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        return (new SendResetPasswordNotificationService())->send($request);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        return (new ResetPasswordService())->reset($request);
    }
    function refreshToken()
    {
        return response()->json([
            "token" => auth()->refresh()
        ]);
    }
}
