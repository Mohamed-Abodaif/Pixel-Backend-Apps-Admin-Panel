<?php

namespace App\Services\WorkSector\UsersModule\EmailVerificationService;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VerificationNotificationSender extends ServiceBaseFunctionality
{

    protected function setRequestKeysToValidationWhenUserSet(): array
    {
        $this->validator->UnsetRulesAndCancel();
        return [];
    }

    /**
     * @return string[]
     * @throws Exception
     */
    protected function setRequestKeysToGeneralValidation(): array
    {
        $validationRules = ["email"];
        $this->validator->OnlyRules($validationRules);
        return $validationRules;
    }



    private function generateUserVerificationToken(): string | bool
    {
        $this->user->verification_token = substr(md5(rand(0, 9) . $this->user->email . time()), 0, 32);
        $this->user->email_verified_at = null;
        return $this->user->save();
    }

    /**
     * @param Request|array $request
     * @return JsonResponse
     */
    public function send(Request | array $request = []): JsonResponse
    {
        try {
            if ($this->setProcessableUser($request)) {
                if ($this->generateUserVerificationToken()) {
                    $this->user->sendEmailVerificationNotification();
                    return Response::success([], ["Verification Token Has Sent Successfully"]);
                }
                return Response::error([], ["Failed To Send Verification Token !"]);
            }
            return Response::error([], ["Failed To Send Verification Token ... User Not Found !"]);
        } catch (Exception $e) {
            return Response::error([$e->getMessage()]);
        }
    }
}