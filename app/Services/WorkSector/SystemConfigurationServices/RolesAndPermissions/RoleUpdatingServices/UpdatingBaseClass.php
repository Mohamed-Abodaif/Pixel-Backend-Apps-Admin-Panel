<?php

namespace App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUpdatingServices;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\CustomLibs\ValidatorLib\Validator;
use App\CustomLibs\ValidatorLib\ArrayValidator;
use App\Http\Requests\RoleRequests\RoleUpdatingRequest;
use App\Models\WorkSector\SystemConfigurationModels\RoleModel;

abstract class UpdatingBaseClass
{
    protected Validator $validator;
    protected RoleModel $role;
    protected array $data;
    protected array $DefaultRoles;


    /**
     * @throws Exception
     */
    public function __construct(RoleModel $role)
    {
        $this->role = $role;
        $this->DefaultRoles = config("acl.roles");
    }

    /**
     * @param Request|array $request
     * @return $this
     * @throws Exception
     */
    protected function initValidator(Request | array $request): self
    {
        $request->merge(["role_id" => $this->role->id]);
        $this->validator = new ArrayValidator($this->getRequestFormClass(), $request);
        return $this;
    }
    protected function IsDefaultRole(): bool
    {
        return in_array($this->role->name, $this->DefaultRoles);
    }

    protected function getRequestFormClass(): string
    {
        return RoleUpdatingRequest::class;
    }

    /**
     * @return $this
     */
    protected function getRequestData(): self
    {
        $this->data = $this->validator->getRequestData();
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function validateData(): bool
    {
        $result = $this->validator->validate();
        if (is_array($result)) {
            throw new Exception(join(" , ", $result));
        }
        return true;
    }


    //Here We Called The Common Operations (Validation .... etc)
    public function change(Request | array $data): JsonResponse
    {
        try {
            $this->initValidator($data)->getRequestData();
            $this->validateData();
            return $this->changerFun();
        } catch (Exception $e) {
            return $this->getErrorResponse([$e->getMessage()]);
        }
    }

    protected function getErrorResponse(array $messages): JsonResponse
    {
        return Response::error($messages);
    }

    /**
     * @return JsonResponse
     * This Method Will Execute The Desired Updating Actions
     */
    abstract protected function changerFun(): JsonResponse;
}