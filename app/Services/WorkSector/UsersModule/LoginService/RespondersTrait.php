<?php

namespace App\Services\UserManagementServices\LoginService;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Models\WorkSector\UsersModule\User;
use App\Http\Resources\WorkSector\UsersModule\UserResource;

trait RespondersTrait
{

    private function deleteUserPreviousTokens(User $user): bool
    {
        return $user->tokens()->delete();
    }

    private function prepareDataToSend(User $user): array
    {
        $this->deleteUserPreviousTokens($user);
        return (new UserResource($user))->toArray(app('request'));
    }

    private function getSuccessResponse(User $user,$message, $statusCode = 201): JsonResponse
    {
        return Response::success(
            $this->prepareDataToSend($user),
            $message,
            $statusCode
        );
    }

    private function getErrorResponse(array $messages = [], $statusCode = 406): JsonResponse
    {
        return Response::error(
            !empty($messages) ? $messages : "Login Failed !",
            $statusCode
        );
    }
}
