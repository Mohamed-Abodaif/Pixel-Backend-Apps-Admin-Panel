<?php

namespace App\Services\WorkSector\UsersModule;

use Exception;
use App\Helpers\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Services\WorkSector\UsersModule\UserUpdatingService;
use App\Services\WorkSector\UsersModule\EmailVerificationService\VerificationNotificationSender;

class UserEmailChangerService extends UserUpdatingService
{

    private bool $needToNewVerification = false;

    private function checkUserEmailChanging(array $data)
    {
        if (isset($data["email"]) && $this->user->email != $data["email"]) {
            $this->needToNewVerification = true;
        }
    }

    /**
     * @return string[]
     * @throws Exception
     */
    public function getRequestKeysToValidation(): array
    {
        $this->validator->OnlyRules(["email"]);
        return ["email"];
    }

    //every service will define its own change functionality
    protected function changerFun(array $data): JsonResponse
    {
        $this->checkUserEmailChanging($data);
        if ($this->needToNewVerification) {
            $this->user->email = $data["email"];
            if ($this->user->save()) {
                $sendingResponse = (new VerificationNotificationSender())->setUser($this->user)->send();
                if (Helpers::IsResponseStatusSuccess($sendingResponse)) {
                    return Response::success([], ["Email Has Changed Successfully , And Verification Token Has Sent"]);
                }

                return Response::error(Helpers::getResponseMessages($sendingResponse));
            }
            return Response::error(["Failed To Change Email"]);
        }
        return Response::error(["There Is No Changing In Email"]);
    }
}
