<?php

namespace App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUpdatingServices;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Permission;

class RoleInfoUpdatingService extends UpdatingBaseClass
{

    /**
     * @return self
     * @throws Exception
     */
    protected function updateRole() : self
    {
        if($this->IsDefaultRole()){throw new Exception("Can't Update Any Default Role Or Its Permissions") ;}
        if($this->role->update(["name" => $this->data["name"]])){return $this;}
        throw new Exception("Failed To Update The Given Role") ;
    }

    protected function PermissionIDSGetter() : array
    {
        //return Permission::whereIn("name" , $this->data["permissions"])->pluck("id")->toArray();
        return $this->data["permissions"];
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function updatePermissions() : self
    {
        if($this->role->syncPermissions($this->PermissionIDSGetter())){return $this;}
        throw new Exception("Failed To Update Roles 's Permissions");
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    protected function changerFun(): JsonResponse
    {
        DB::beginTransaction();
        $this->updateRole()->updatePermissions();

        //If No Exception Is Thrown The Transaction Will Be Commit
        DB::commit();
        return Response::success([] ,["Role Has Been Updated Successfully"]);
    }

    protected function getErrorResponse(array $messages) : JsonResponse
    {
        DB::rollBack();
        return Parent::getErrorResponse($messages);
    }
}
