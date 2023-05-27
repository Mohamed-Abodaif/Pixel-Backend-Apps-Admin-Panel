<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\RolesAndPermissions;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\Permission\Models\Permission;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\RoleModel;
use App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleStoringService;
use App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleDeletingService;
use App\Http\Resources\WorkSector\SystemConfigurationResources\RolesAndPermissions\RoleShowResource;
use App\Http\Resources\WorkSector\SystemConfigurationResources\RolesAndPermissions\RolesListResource;
use App\Http\Resources\WorkSector\SystemConfigurationResources\RolesAndPermissions\PermissionsResource;
use App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUpdatingServices\RoleDisablingSwitcher;
use App\Services\WorkSector\SystemConfigurationServices\RolesAndPermissions\RoleUpdatingServices\RoleInfoUpdatingService;

class RolesController extends Controller
{

    protected $filterable = [
        'name',
        'status'
    ];

    // public function __construct()
    // {
    //     $this->middleware('permission:read_sc-roles-and-permissions')->only(['index']);
    //     $this->middleware('permission:create_sc-roles-and-permissions')->only(['store']);
    //     $this->middleware('permission:read_sc-roles-and-permissions')->only(['show']);
    //     $this->middleware('permission:edit_sc-roles-and-permissions')->only(['update']);
    //     $this->middleware('permission:delete_sc-roles-and-permissions')->only(['destroy']);
    //     $this->middleware('permission:import_sc-roles-and-permissions')->only(['importRoles']);
    //     $this->middleware('permission:export_sc-roles-and-permissions')->only(['exportRoles']);
    // }

    // public function index(Request $request)
    // {
    //     $data = QueryBuilder::for(Role::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
    //}

    public function index(Request $request)
    {
        $data = RoleModel::orderBy('disabled', 'asc')->orderBy('default', 'desc')->get();
        return RolesListResource::collection($data);
    }

    public function list(Request $request)
    {
        $data = QueryBuilder::for(RoleModel::class)
            ->allowedFilters(['name'])
            ->activeRole()
            ->customOrdering('created_at', 'desc')
            ->get();
        return RolesListResource::collection($data);
    }

    function show(RoleModel $role)
    {
        return new RoleShowResource($role);
    }

    public function store(Request $request): JsonResponse
    {
        return (new RoleStoringService())->create($request);
    }

    public function update(RoleModel $role, Request $request): JsonResponse
    {
        return (new RoleInfoUpdatingService($role))->change($request);
    }


    //Make It Enabled or Disabled
    public function switchRole(Request $request, RoleModel $role): JsonResponse
    {
        return (new RoleDisablingSwitcher($role))->change($request);
    }

    public function destroy(RoleModel $role): JsonResponse
    {
        return (new RoleDeletingService($role))->delete();
    }

    public function allPermission()
    {
        $permissions = Permission::pluck('name')->get();
        return PermissionsResource::collection($permissions);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function importRoles(Request $request)
    {
        return (new ImportBuilder())
            ->file($request->file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Role::create($item);
            })
            ->import();
    }

    /**
     * @param Request $request
     * @return ExportBuilder
     * @throws \App\Exceptions\FileTypeNotSupported
     */
    public function exportRoles(Request $request)
    {
        $taxes = QueryBuilder::for(Role::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Roles" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Roles');
    }
}
