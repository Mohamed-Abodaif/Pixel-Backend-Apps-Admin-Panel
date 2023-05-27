<?php

namespace App\Services\WorkSector\UsersModule\UpdatingUserByAdminService;

use Exception;
use App\Helpers\Helpers;
use App\Http\Requests\Users\UserUpdatingRequest;
use App\Services\WorkSector\UsersModule\UserRoleChanger;
use App\Services\WorkSector\UsersModule\UserProfileUpdatingService\UserProfileUpdatingService;

class UpdatingUserByAdminService extends UserProfileUpdatingService
{
    protected function getRequestForm(): string
    {
        return UserUpdatingRequest::Class;
    }
    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    private function updateUserRole(array $data): bool
    {
        $roleChangingService = (new UserRoleChanger($this->user))->change($data);
        if (Helpers::IsResponseStatusSuccess($roleChangingService)) {
            return true;
        }
        throw new Exception(join(" , ", Helpers::getResponseMessages($roleChangingService)));
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    private function updateUserRelationships(array $data): self
    {
        $this->updateUserRole($data);
        return $this;
    }
}
