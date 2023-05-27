<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DefaultRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        $role = Role::where('name','user')->first();
        
        if(!$role)
        {
            Role::create(['name'=>'user','guard_name'=>'api','disabled'=>0]);
        }
      
        $role->givePermissionTo([
            "read_personal-sector-dashboard",
            "read_personal-sector-expenses",
            "create_personal-sector-expenses",
            "edit_personal-sector-expenses",
            "delete_personal-sector-expenses",
            "read_personal-sector-custody",
            "create_personal-sector-custody",
            "edit_personal-sector-custody",
            "delete_personal-sector-custody"
        ]);

    }
}
