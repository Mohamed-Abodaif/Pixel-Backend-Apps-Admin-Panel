<?php

namespace App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions;

use Exception;
use  Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Permission;
use App\CustomLibs\ValidatorLib\Validator;
use App\CustomLibs\ValidatorLib\ArrayValidator;
use App\Http\Requests\RoleRequests\RoleStoringRequest;
use App\Models\WorkSector\SystemConfigurationModels\RoleModel;

class RoleStoringService
{

    private Validator $validator;
    private Model | RoleModel $role;


    /**
     * @param Request|array $request
     * @return $this
     * @throws Exception
     */
    public function initValidator(Request | array $request): self
    {
        $this->validator = new ArrayValidator(RoleStoringRequest::class, $request);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     * @throws Exception
     */
    private function createRole(string $name): self
    {
        $role = RoleModel::create(["name" => $name]);
        if ($role) {
            $this->role = $role;
            return $this;
        }
        throw new Exception("Failed To Create The Given Role !");
    }

    private function PermissionsArrayHandler(array $permissions): array
    {
        return Permission::whereIn("name", $permissions)->orderBy("id")->pluck("id")->toArray();
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    private function createPermissionRelationships(array $data): self
    {
        $permissions = $this->PermissionsArrayHandler($data["permissions"]);
        if ($this->role->syncPermissions($permissions)) {
            return $this;
        }
        throw  new Exception("Permissions Has Not Been Bind To The Given Rule .... Operation Has Been Canceled !");
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function validateData(): bool
    {
        $result = $this->validator->validate();
        if (is_array($result)) {
            throw new Exception(join(" , ", $result));
        }
        return true;
    }

    public function create(Request | array $request): JsonResponse
    {
        try {
            $this->initValidator($request);
            $data = $this->validator->getRequestData();
            $this->validateData();
            //Validation Exceptions (If There are) Will Be Thrown Before DB Transaction
            DB::beginTransaction();
            $this->createRole($data["name"])
                ->createPermissionRelationships($data);
            //If No Exception Is Thrown .... Transaction Will Be Commit
            DB::commit();
            return Response::success([], ["Role Has Been Created Successfully"]);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::error([$e->getMessage()]);
        }
    }
}
