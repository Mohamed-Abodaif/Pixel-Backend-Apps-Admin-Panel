<?php

namespace App\Jobs\RoleJobs;

use Exception;
use App\Models\RoleModel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement\SwitchAllRoleUsersToDefaultRole;

class SwitchAllRoleUsersToDefaultRoleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private SwitchAllRoleUsersToDefaultRole $service;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SwitchAllRoleUsersToDefaultRole $service)
    {
        $this->service = $service;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $this->service->updateUsersRole();
    }
}
