<?php

namespace App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\WorkSector\SystemConfigurationModels\RoleModel;
use App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement\RoleUsersManager;
use App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement\SwitchAllRoleUsersToDefaultRole;

class RoleDeletingService
{
    private RoleModel $role;
    private RoleUsersManager $usersManager;
    protected array $DefaultRoles;

    public function __construct(RoleModel $role)
    {
        $this->role = $role;
        $this->DefaultRoles = config("acl.roles");
        $this->usersManager = new SwitchAllRoleUsersToDefaultRole($role);
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function switchUsersToDefaultRole(): bool
    {
        return $this->usersManager->switchRoleUsers();
    }

    protected function IsDefaultRole(): bool
    {
        return in_array($this->role->name, $this->DefaultRoles);
    }

    /**
     * @return true
     * @throws Exception
     */
    private function deleteSoftly(): bool
    {
        $this->role->deleted_at = now();
        $this->role->disabled = 1;
        if ($this->role->save()) {
            return true;
        }
        throw new Exception("Failed To Delete Role");
    }

    /**
     * @return true
     * @throws Exception
     */
    private function forcedDelete(): bool
    {
        if ($this->role->forceDelete()) {
            return true;
        }
        throw new Exception("Failed To Delete Role");
    }

    public function delete(bool $forcedDelete = false): JsonResponse
    {
        try {
            if ($this->IsDefaultRole()) {
                throw new Exception("Can't Delete Any Default Role Or Its Permissions");
            }
            DB::beginTransaction();
            //If It Fails To Switch Users .... An Exception Will Be Thrown And The Deleting Operation Will Stop
            $this->switchUsersToDefaultRole();

            //We Don't Check If Deleting Methods Gets true or False ... Because They Didn't Throw Any Exception That Means The Deleting Is Successful
            if ($forcedDelete) {
                $this->forcedDelete();
            } else {
                $this->deleteSoftly();
            }

            DB::commit();
            return Response::success([], ["Role Has Been Deleted Successfully"]);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::error([$e->getMessage()]);
        }
    }
}