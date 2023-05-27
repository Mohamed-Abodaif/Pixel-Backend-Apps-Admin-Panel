<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = config('acl.permissions.all');
        //list all $permissions
        foreach ($permissions as $permission)
         {
            Permission::create(['guard_name'=>'api','name' => $permission]);
         }

    }

}
