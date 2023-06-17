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
use App\Models\WorkSector\SystemConfigurationModels\Branch;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\BranchResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchUpdatingService;

class BranchesController extends Controller
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
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importBranches']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportBranches']);

    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(Branch::class)->with('parent')
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    public function show(Branch $department)
    {
        return new SingleResource($department);
    }

    function list()
    {
        $data = QueryBuilder::for(Branch::class)->with('parent')
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return BranchResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        return (new BranchStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param Branch $branch
     * @return JsonResponse
     */
    public function update(Request $request, Branch $branch): JsonResponse
    {
        return (new BranchUpdatingService($branch))->update($request);
    }

    /**
     * @param Branch $department
     * @return JsonResponse
     */
    public function destroy(Branch $branch): JsonResponse
    {
        return (new BranchDeletingService($branch))->delete();
    }

    public function importBranches(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Branch::create($item);
            })
            ->import();
    }

    public function exportBranches(Request $request)
    {
        $taxes = QueryBuilder::for(Branch::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Branches" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Branches')->build();
    }
}
