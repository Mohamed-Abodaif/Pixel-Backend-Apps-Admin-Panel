<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use App\Export\ExportBuilder;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\ServicesCategoriesResource;
use App\Models\WorkSector\SystemConfigurationModels\ServiceCategory;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ServiceCategoriesOperations\ServiceCategoryDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ServiceCategoriesOperations\ServiceCategoryStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ServiceCategoriesOperations\ServiceCategoryUpdatingService;
use Illuminate\Http\JsonResponse;

class ServiceCategoriesController extends Controller
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
        $data = QueryBuilder::for(ServiceCategory::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return response()->success(['list' => $data], ['Asset Category created successfully.'], 200);
    }

    function list(Request $request)
    {
        $data = QueryBuilder::for(ServiceCategory::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->select('id', 'name')->get();
        return ServicesCategoriesResource::collection($data);
    }

    public function show($id)
    {
        $item = ServiceCategory::findOrFail($id);
        return new SingleResource($item);
    }

    public function store(Request $request)
    {
        return (new ServiceCategoryStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param ServiceCategory $category
     * @return JsonResponse
     */
    public function update(Request $request, ServiceCategory $category): JsonResponse
    {
        return (new ServiceCategoryUpdatingService($category))->update($request);
    }

    

    /**
     * @param ServiceCategory $category
     * @return JsonResponse
     */
    public function destroy(ServiceCategory $category): JsonResponse
    {
        return (new ServiceCategoryDeletingService($category))->delete();
    }


    public function importProductsCategoies(ImportFile $import)
    {
        $file = $import->file;
        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return ServiceCategory::create($item);
            })
            ->import();
    }

    public function exportProductsCategoies(Request $request)
    {
        $asset_types = QueryBuilder::for(ServiceCategory::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Asset Categories" => ExportBuilder::generator($asset_types)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']])
            ->name('Asset Categories')->build();
    }
}
