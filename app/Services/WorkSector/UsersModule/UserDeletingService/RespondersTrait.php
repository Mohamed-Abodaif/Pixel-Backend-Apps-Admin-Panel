<?php

namespace App\Services\WorkSector\UsersModule\UserDeletingService;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

trait RespondersTrait
{

    private function getSuccessResponse($statusCode = 201): JsonResponse
    {
        return Response::success(
            [],
            ["User Has Been deleted Successfully !"],
            $statusCode
        );
    }

    private function getErrorResponse(array $messages = [], $statusCode = 406): JsonResponse
    {
        return Response::error(
            !empty($messages) ? $messages : ["Failed To Delete User !"],
            $statusCode
        );
    }
}
