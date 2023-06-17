<?php

namespace App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement;

use App\Jobs\RoleJobs\SwitchBackAllRolePreviousUsersJob;
use App\Models\WorkSector\UsersModule\User;
use Exception;

class SwitchBackAllRolePreviousUsers extends RoleUsersManager
{

    protected function setRoleUserIDS(): self
    {
        $this->roleUserIDS = User::where("previous_role_id", $this->role->id)->pluck("id")->toArray();
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function updateUsersRole(): bool
    {
        $updatedData = ["role_id" => $this->role->id, "previous_role_id" => null];
        if (User::whereIn("id", $this->roleUserIDS)->update($updatedData)) {
            return true;
        }
        throw new Exception("Failed To Switch Related Previous Users To The Role " . $this->role->name);
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function initToSwitchFoundUsers(): bool
    {
        return true;
    }

    protected function getJobClass(): string
    {
        return SwitchBackAllRolePreviousUsersJob::class;
    }
}