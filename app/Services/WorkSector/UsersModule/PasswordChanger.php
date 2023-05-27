<?php

namespace App\Services\UserManagementServices\UserUpdatingService;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\WorkSector\UsersModule\UserUpdatingService;

class PasswordChanger extends UserUpdatingService
{
    public function getRequestKeysToValidation(): array
    {
        $this->validator->UnsetRulesAndCancel();
        return [];
    }
    protected function getRequestForm(): string
    {
        return ResetPasswordRequest::class;
    }


    protected function changerFun(array $data): JsonResponse
    {
        if (Hash::check($data["old_password"], $this->user->password)) {
            $this->user->update(['password' => Hash::make($data["new_password"])]);
            return Response::success([], ["Updated Successfully"], 201);
        }

        return Response::error(["Failed To Update Password"]);
    }
}
