<?php

namespace App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUpdatingServices;


use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\RoleRequests\RoleSwitchingRequest;
use App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement\RoleUsersManager;
use App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement\SwitchBackAllRolePreviousUsers;
use App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement\SwitchAllRoleUsersToDefaultRole;

class RoleDisablingSwitcher extends UpdatingBaseClass
{
    private RoleUsersManager $usersManager;

    /**
     * @return $this
     */
    //Use this Method When You Want To Work Without queue Job
    public function setUsersManager(): self
    {
        if ($this->data["disabled"] == 0) {
            $this->usersManager = new SwitchBackAllRolePreviousUsers($this->role);
            return $this;
        }
        $this->usersManager = new SwitchAllRoleUsersToDefaultRole($this->role);
        return $this;
    }

    protected function SwitchUsersRole(): bool
    {
        $this->setUsersManager();
        return $this->usersManager->switchRoleUsers();
    }

    protected function getRequestFormClass(): string
    {
        return RoleSwitchingRequest::class;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function updateRoleStatus(): bool
    {
        $this->role->disabled = $this->data["disabled"];
        if ($this->role->save()) {
            return true;
        }
        throw new Exception("Failed To Change Role 's Status");
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    protected function changerFun(): JsonResponse
    {
        if ($this->IsDefaultRole()) {
            throw new Exception("Can't Change Disabling Status Of A Default Role");
        }
        DB::beginTransaction();

        //If No Exception Is Thrown ... The Status Of Role Has Been Changed
        //If Its Status Changing Has Failed ... We Don't Need To Continue In Transaction So We Stop It By Exception Throwing
        $this->updateRoleStatus();

        //If No Exception Is Thrown ... All Related Users Has Been Switched To Another Role (Default Role Or The Activated Role)
        //Every Thing Will Be By Queue Job ... Make Sure To Run The Cron Job On Your Server
        $this->SwitchUsersRole();

        DB::commit();
        return Response::success([], ["Role Switched Successfully."]);
    }

    protected function getErrorResponse(array $messages): JsonResponse
    {
        DB::rollBack();
        return Parent::getErrorResponse($messages);
    }
}
