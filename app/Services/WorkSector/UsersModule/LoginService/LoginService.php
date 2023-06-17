<?php

namespace App\Services\WorkSector\UsersModule\LoginService;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use App\CustomLibs\ValidatorLib\Validator;
use App\Models\WorkSector\UsersModule\User;
use App\CustomLibs\APIResponder\APIResponder;
use App\CustomLibs\ValidatorLib\JSONValidator;
use App\Http\Requests\WorkSector\UsersModule\LoginRequest;
use App\Services\WorkSector\UsersModule\UserCreatingService\RespondersTrait;

class LoginService
{
    private Validator $validator;
    private APIResponder $APIResponder;

    use RespondersTrait;

    /**
     * @param Request|array $request
     * @return $this
     * @throws Exception
     */
    private function initValidator(Request | array $request): self
    {
        $this->validator = new JSONValidator(LoginRequest::class, $request);
        return $this;
    }

    private function validateData(): bool | JsonResponse
    {
        return $this->validator->validate();
    }

    // $extraLoginConditions must be like :
    // [ ["column" => "id" , "operator" => "=" , "value" => "value" ] ]
    private function addExtraLoginConditionsToBuilder(Builder $queryBuilder, array $extraLoginConditions): Builder
    {
        foreach ($extraLoginConditions as $condition) {
            $queryBuilder->where($condition["column"], $condition["operator"] ?? "=", $condition["value"]);
        }
        return $queryBuilder;
    }

    // $extraLoginConditions must be like :
    // ["column1" => "value1" , "column2" => "value2"]
    /**
     * @param array $data
     * @param array $extraLoginConditions
     * @return User|bool
     * @throws Exception
     */
    private function checkUserData(array $data, array $extraLoginConditions = []): User | bool
    {
        $userBuilder = User::where("email", $data["email"]);

        /**  @var User $user */
        $user = $this->addExtraLoginConditionsToBuilder($userBuilder, $extraLoginConditions)->first();
        if ($user  &&  $this->checkUserPassword($user,  $data)) {
            return $user;
        }
        throw new Exception("Login Failed , User Not Found !");
    }

    private function checkUserPassword(User $user, array $data): bool
    {
        return Hash::check($data["password"], $user->password);
    }

    /**
     * @param User $user
     * @return bool
     * @throws Exception
     */
    private function checkApprovementStatus(User $user): bool
    {
        if ($user->accepted_at) {
            return true;
        }
        throw new Exception("Login Failed , Your Account Not Approved Yet !");
    }

    /**
     * @param User $user
     * @return bool
     * @throws Exception
     */
    private function checkVerificationStatus(User $user): bool
    {
        if ($user->email_verified_at) {
            return true;
        }
        throw new Exception("Login Failed , Your Email Is Not Verified Yet !");
    }


    /**
     * @param Request|array $request
     * @param array $extraLoginConditions
     * @return JsonResponse
     */
    public function login(Request | array $request, array $extraLoginConditions = []): JsonResponse
    {
        try {
            $this->initValidator($request);
            $validationResult = $this->validateData();
            if ($validationResult instanceof JsonResponse) {
                return $validationResult;
            }

            $data = $this->validator->getRequestData();
            $user = $this->checkUserData($data, $extraLoginConditions);
            $this->checkApprovementStatus($user);
            $this->checkVerificationStatus($user);
            return $this->getSuccessResponse($user, ['User Logged in Successfully'], 200);
        } catch (Exception $e) {
            return $this->getErrorResponse([$e->getMessage()]);
        }
    }
}
