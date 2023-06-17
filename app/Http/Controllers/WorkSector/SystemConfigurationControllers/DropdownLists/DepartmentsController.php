<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\Department;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\DepartmentResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentUpdatingService;

class DepartmentsController extends Controller
{
    protected $filterable = [
        'name',
        'status'
    ];

    public function __construct()
    {
        // $this->middleware('permission:read_sc-dropdown-lists')->only(['index']);
        // $this->middleware('permission:create_sc-dropdown-lists')->only(['store']);
        // $this->middleware('permission:read_sc-dropdown-lists')->only(['show']);
        // $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
        // $this->middleware('permission:delete_sc-dropdown-lists')->only(['destroy']);
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importDepartmentes']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportDepartmentes']);

    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(Department::class)->with('parent')
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    public function show(Department $department)
    {
        return new SingleResource($department);
    }

    function list()
    {
        $data = QueryBuilder::for(Department::class)->with('parent')
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return DepartmentResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        return (new DepartmentStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param Department $department
     * @return JsonResponse
     */
    public function update(Request $request, Department $department): JsonResponse
    {
        return (new DepartmentUpdatingService($department))->update($request);
    }

    /**
     * @param Department $department
     * @return JsonResponse
     */
    public function destroy(Department $department): JsonResponse
    {
        return (new DepartmentDeletingService($department))->delete();
    }

    public function importDepartmentes(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Department::create($item);
            })
            ->import();
    }

    public function exportDepartmentes(Request $request)
    {
        $taxes = QueryBuilder::for(Department::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Departmentes" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Departmentes')->build();
    }
}
