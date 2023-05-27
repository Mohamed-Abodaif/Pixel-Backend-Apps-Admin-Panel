<?php

namespace App\Services\WorkSector\UsersModule\AccountStatusChanger;

use App\Services\WorkSector\UsersModule\AccountStatusChanger\AccountStatusChanger;


class EmployeeAccountStatusChanger extends AccountStatusChanger
{

    protected function needToSetRoleAndDepartment(int $status): bool
    {
        return false;
    }
}
