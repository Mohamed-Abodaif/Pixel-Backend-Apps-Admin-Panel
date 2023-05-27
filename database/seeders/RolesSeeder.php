<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = config('acl.roles');
        $permissions = config('acl.permissions');
        $this->temporaryPermissionSeeder($roles , $permissions["all"]);


        ////////////////////Execute This Code After Specifying Default Roles Permissions
        //Looping on all roles and setting their permissions
//        foreach ($roles as $role)
//         {
//            $roleOb = Role::create(['guard_name'=>'api','name' => $role]);
//            $roleOb?->syncPermissions(
//                $permissions[str_replace(" " , "_" , $role)]
//            );
//
//         }
    }

    private function temporaryPermissionSeeder(array $roles , $allPermissions)
    {
        foreach ($roles as $role)
         {
            $roleOb = Role::create(['guard_name'=>'api','name' => $role]);
            $roleOb?->syncPermissions( $allPermissions );

         }

    }

}
