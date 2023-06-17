<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use App\Export\ExportBuilder;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\MeasurementUnit;
use App\Http\Requests\WorkSector\SystemConfigurations\MeasurementUnits\MeasurementUnitRequest;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\MeasuremenstUnitesResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\MeasurementUnitsOperations\MeasurementUnitDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\MeasurementUnitsOperations\MeasurementUnitStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\MeasurementUnitsOperations\MeasurementUnitUpdatingService;
use Illuminate\Http\JsonResponse;

class MeasurementUnitesController extends Controller
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
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importProductsCategoies']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportProductsCategoies']);
    }
    //
    public function index(Request $request)
    {
        $data = QueryBuilder::for(MeasurementUnit::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return response()->success(['list' => $data], ['Asset Category created successfully.'], 200);
    }

    function list(Request $request)
    {
        $data = QueryBuilder::for(MeasurementUnit::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->select('id', 'name')->get();
        return MeasuremenstUnitesResource::collection($data);
    }

    public function show($id)
    {
        $item = MeasurementUnit::findOrFail($id);
        return new SingleResource($item);
    }

    public function store(Request $request)
    {
        return (new MeasurementUnitStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param MeasurementUnit $measurementUnit
     * @return JsonResponse
     */
    public function update(Request $request, MeasurementUnit $measurementUnit): JsonResponse
    {
        return (new MeasurementUnitUpdatingService($measurementUnit))->update($request);
    }

    /**
     * @param MeasurementUnit $measurementUnit
     * @return JsonResponse
     */
    public function destroy(MeasurementUnit $measurementUnit): JsonResponse
    {
        return (new MeasurementUnitDeletingService($measurementUnit))->delete();
    }

    public function importProductsCategoies(ImportFile $import)
    {
        $file = $import->file;
        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return MeasurementUnit::create($item);
            })
            ->import();
    }

    public function exportProductsCategoies(Request $request)
    {
        $asset_types = QueryBuilder::for(MeasurementUnit::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "measurement Unites" => ExportBuilder::generator($asset_types)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']])
            ->name('measurement Unites')->build();
    }
}
