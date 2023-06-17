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
use App\Models\WorkSector\SystemConfigurationModels\Area;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\AreaResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AreasOperations\AreaStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AreasOperations\AreaDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AreasOperations\AreaUpdatingService;

class AreasController extends Controller
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
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importAreas']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportAreas']);

    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(Area::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    public function show(Area $department)
    {
        return new SingleResource($department);
    }

    function list()
    {
        $data = QueryBuilder::for(Area::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return AreaResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        return (new AreaStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param Area $department
     * @return JsonResponse
     */
    public function update(Request $request, Area $department): JsonResponse
    {
        return (new AreaUpdatingService($department))->update($request);
    }

    /**
     * @param Area $department
     * @return JsonResponse
     */
    public function destroy(Area $department): JsonResponse
    {
        return (new AreaDeletingService($department))->delete();
    }

    public function importAreas(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Area::create($item);
            })
            ->import();
    }

    public function exportAreas(Request $request)
    {
        $taxes = QueryBuilder::for(Area::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Areas" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Areas')->build();
    }
}
