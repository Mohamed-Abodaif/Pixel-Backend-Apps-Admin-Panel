<?php

namespace App\Services\WorkSector\UsersModule\AccountStatusChanger;

use App\Services\WorkSector\UsersModule\AccountStatusChanger\AccountStatusChanger;



class SignUpAccountStatusChanger extends AccountStatusChanger
{

    protected function needToSetRoleAndDepartment(int $status): bool
    {
        return $status == 1;
    }
}
