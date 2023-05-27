<?php

namespace App\Services\WorkSector\UsersModule\UserCreatingService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ValidationMethodsTrait
{

    private function validateRequestData(): bool | JsonResponse
    {
        //validation Operation
        return $this->validator->validate();
    }
}
