<?php

namespace App\Services\WorkSector\UsersModule\UserCreatingService;

use Spatie\Permission\Models\Role;
use App\Models\WorkSector\UsersModule\User;

trait UserRelationshipsHandlerTrait
{


    private function associateUserToRelationships(User $user, array $data): void
    {
        //If There Are Any Relationships ... Handle It Here
    }
}
