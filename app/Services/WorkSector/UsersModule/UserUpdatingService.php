<?php

namespace App\Services\WorkSector\UsersModule;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\CustomLibs\ValidatorLib\Validator;
use App\Models\WorkSector\UsersModule\User;
use App\CustomLibs\ValidatorLib\JSONValidator;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\WorkSector\UsersModule\ValidationMethods;

abstract class UserUpdatingService
{

    use ValidationMethods;

    protected User | Authenticatable $user;
    protected Validator $validator;

    public function __construct(User | Authenticatable $user)
    {
        $this->user = $user;
    }

    //the validation 's common operation will be run .... then the main functionality will be called
    public function change(Request | array $request): JsonResponse
    {
        try {
            $this->initValidator($request);
            $data = $this->validator->getRequestData();
            $dataValidationResult = $this->validator->validate();
            if ($dataValidationResult instanceof JsonResponse) {
                return $dataValidationResult;
            }
            return $this->changerFun($data);
        } catch (Exception $e) {
            $this->actionWithErrorResponding();
            return Response::error([$e->getMessage()]);
        }
    }

    //every service will define its own change functionality
    abstract protected function changerFun(array $data): JsonResponse;
    protected function actionWithErrorResponding(): void
    {
        return;
    }
}
