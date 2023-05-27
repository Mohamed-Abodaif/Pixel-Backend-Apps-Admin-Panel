<?php

namespace App\Services\WorkSector\UsersModule;


use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Users\UpdateUserRole;
use App\Services\WorkSector\UsersModule\UserUpdatingService;
use App\Models\WorkSector\SystemConfigurationModels\RoleModel;

class UserRoleChanger extends UserUpdatingService
{

    public function getRequestKeysToValidation(): array
    {
        $this->validator->AllRules();
        return ["allRules"];
    }

    protected function getRequestForm(): string
    {
        return UpdateUserRole::class;
    }

    /**
     * @param array $data
     * @return RoleModel
     * @throws Exception
     * Here We Make Sure That Role Is Exists and it is not deleted softly
     */
    protected function getRoleModel(array $data): RoleModel
    {
        $role = RoleModel::activeRole()->where("id", $data["role_id"])->select("id")->first();
        if ($role) {
            return $role;
        }
        throw new Exception("The Given Role Is Not Exists In Our Database ");
    }

    protected function changerFun(array $data): JsonResponse
    {
        $role = $this->getRoleModel($data);
        $this->user->previous_role_id = $this->user->role_id;
        $this->user->role_id = $role->id;
        if ($this->user->save()) {
            return Response::success([], ["User's Role Has Been Updated Successfully"], 201);
        }

        return Response::error(["Failed To Update User's Role"]);
    }
}