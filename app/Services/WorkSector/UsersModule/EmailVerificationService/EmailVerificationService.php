<?php

namespace App\Services\WorkSector\UsersModule\EmailVerificationService;


use App\Notifications\UserNotifications\EmailVerificationNotifications\EmailVerificationCompletedNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EmailVerificationService extends ServiceBaseFunctionality
{

    protected function setRequestKeysToGeneralValidation(): array
    {
        $validationRules = ["email", "token"];
        $this->validator->OnlyRules($validationRules);
        return $validationRules;
    }
    protected function setRequestKeysToValidationWhenUserSet(): array
    {
        $validationRules = ["token"];
        $this->validator->OnlyRules($validationRules);
        return $validationRules;
    }

    /**
     * @param string $tokenKey
     * @return bool
     * @throws Exception
     */
    private function checkToken(string $tokenKey): bool
    {
        if ($this->data[$tokenKey] !== $this->user->verification_token) {
            throw new Exception("The Given Token doesn't Match With User Verification Token's Value");
        }
        return  true;
    }

    private function verifyUser(): bool
    {
        $this->user->verification_token = null;
        $this->user->email_verified_at = now();
        return $this->user->save();
    }

    /**
     * @param array|Request $request
     * @return  JsonResponse
     */
    public function verify(array |Request $request): JsonResponse
    {
        try {
            if ($this->setProcessableUser($request)) {
                if ($this->checkToken("token")) {
                    if ($this->verifyUser()) {
                        $this->user->notify(new EmailVerificationCompletedNotification());
                        return Response::success([], ["Verification Completed Successfully"]);
                    }
                    return Response::error(["Failed To Verify The User"]);
                }
            }
            return Response::error(["Failed To Verify The User ... User Not Found"]);
        } catch (Exception $e) {
            return Response::error([$e->getMessage()]);
        }
    }
}
