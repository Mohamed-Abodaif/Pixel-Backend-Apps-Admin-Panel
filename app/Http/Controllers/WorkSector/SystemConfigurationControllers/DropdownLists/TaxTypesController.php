<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\TaxType;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\TaxTypeResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TaxTypesOperations\TaxTypeStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TaxTypesOperations\TaxTypeDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TaxTypesOperations\TaxTypeUpdatingService;

class TaxTypesController extends Controller
{
    protected $filterable = [
        'name',
        'status'
    ];

    // public function __construct()
    // {
    //     $this->middleware('permission:read_sc-dropdown-lists')->only(['index']);
    //     $this->middleware('permission:create_sc-dropdown-lists')->only(['store']);
    //     $this->middleware('permission:read_sc-dropdown-lists')->only(['show']);
    //     $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
    //     $this->middleware('permission:delete_sc-dropdown-lists')->only(['destroy']);
    //     $this->middleware('permission:import_sc-dropdown-lists')->only(['importTaxTypes']);
    //     $this->middleware('permission:export_sc-dropdown-lists')->only(['exportTaxTypes']);
    // }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(TaxType::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);
        return Response::success(['list' => $data]);
    }

    public function list(Request $request)
    {
        $data = QueryBuilder::for(TaxType::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);

        return TaxTypeResource::collection($data);
    }

    public function show(TaxType $type)
    {
        return new SingleResource($type);
    }

    public function store(Request $request): JsonResponse
    {
        return (new TaxTypeStoringService())->create($request);
    }

    public function update(Request $request, TaxType $type): JsonResponse
    {
        return (new TaxTypeUpdatingService($type))->update($request);
    }

    public function destroy(TaxType $type): JsonResponse
    {
        return (new TaxTypeDeletingService($type))->delete();
    }

    public function importTaxTypes(Request $request)
    {
        return (new ImportBuilder())
            ->file($request->file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return TaxType::create($item);
            })
            ->import();
    }

    public function exportTaxTypes(Request $request)
    {
        $taxes = QueryBuilder::for(TaxType::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Tax Types" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Tax Types')->build();
    }
}
