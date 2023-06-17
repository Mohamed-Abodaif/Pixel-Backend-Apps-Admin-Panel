<?php

namespace App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement;

use Exception;
use App\Models\WorkSector\UsersModule\User;
use App\Jobs\RoleJobs\SwitchAllRoleUsersToDefaultRoleJob;
use App\Models\WorkSector\SystemConfigurationModels\RoleModel;

class SwitchAllRoleUsersToDefaultRole extends RoleUsersManager
{

    private string $DefaultRoleName = "User";
    private RoleModel $DefaultRoleModel;

    /**
     * @return $this
     * @throws Exception
     */
    protected function getDefaultRole(): self
    {
        $defaultRole = RoleModel::where("name", $this->DefaultRoleName)->select("id")->first();
        if ($defaultRole) {
            $this->DefaultRoleModel = $defaultRole;
            return $this;
        }
        throw new Exception("There Is No Default Role Can Be Used To Switching " . $this->role->name . "'s  Related Users");
    }

    protected function setRoleUserIDS(): self
    {
        $this->roleUserIDS = User::where("role_id", $this->role->id)->pluck("id")->toArray();
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function updateUsersRole(): bool
    {
        $updatedData = ["role_id"  => $this->DefaultRoleModel->id, "previous_role_id" => $this->role->id];
        if (User::whereIn("id", $this->roleUserIDS)->update($updatedData)) {
            return true;
        }
        throw new Exception("Failed To Switch Related Users To The Default Role " . $this->DefaultRoleName);
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function initToSwitchFoundUsers(): bool
    {
        //Getting Default Role When Made Sure Of Related Users Foundability
        $this->getDefaultRole();
        return true;
    }

    protected function getJobClass(): string
    {
        return SwitchAllRoleUsersToDefaultRoleJob::class;
    }
}
