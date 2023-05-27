<?php

namespace App\Services\WorkSector\UsersModule\ResetPasswordService;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use App\Models\WorkSector\UsersModule\User;
use App\Models\WorkSector\UsersModule\ResetPassword;
use App\Http\Requests\WorkSector\UsersModule\ResetPasswordRequest;
use App\Services\WorkSector\UsersModule\ResetPasswordService\BaseService;

class ResetPasswordService extends BaseService
{
    private ResetPassword $resetPassword;

    protected function getRequestForm(): string
    {
        return ResetPasswordRequest::class;
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    protected function setPasswordResetModel(array $data): self
    {
        $resetPasswordModel = ResetPassword::restPasswordWithToken($data["token"])->latest()->first();
        if ($resetPasswordModel) {
            $this->resetPassword = $resetPasswordModel;
            return $this;
        }
        throw new Exception("Failed To Reset Password For The Given Token !");
    }

    /**
     * @param array $data
     * @return bool
     */
    private function resetUserPassword(array $data): bool
    {
        $newPassword = Hash::make($data["new_password"]);
        return User::where("email", $this->resetPassword->email)->update(["password" => $newPassword]);
    }

    public function reset(Request | array $request): JsonResponse
    {
        try {
            $this->initValidator($request);
            $data = $this->validator->getRequestData();
            $validationResult = $this->validator->validate();
            if ($validationResult instanceof JsonResponse) {
                return $validationResult;
            }

            $this->setPasswordResetModel($data);
            if ($this->resetUserPassword($data)) {
                $this->resetPassword->delete();
                return Response::success([], ["Password Has Been Reset Successfully"], 200);
            }
            return Response::error(["Failed To Reset Password"]);
        } catch (Exception $e) {
            return Response::error([$e->getMessage()]);
        }
    }
}
