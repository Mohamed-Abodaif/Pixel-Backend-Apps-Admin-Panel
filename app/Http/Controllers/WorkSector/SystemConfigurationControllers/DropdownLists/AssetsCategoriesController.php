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
use App\Models\WorkSector\SystemConfigurationModels\AssetsCategory;
use App\Http\Resources\WorkSector\SystemConfigurationResources\AssetsCategoriesResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AssetsCategoriesOperations\AssetsCategoryStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AssetsCategoriesOperations\AssetsCategoryDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\AssetsCategoriesOperations\AssetsCategoryUpdatingService;

class AssetsCategoriesController extends Controller
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
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importAssetsCategories']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportAssetsCategories']);
    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(AssetsCategory::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);
        return Response::success(['list' => $data]);
    }

    function list()
    {
        $data = QueryBuilder::for(AssetsCategory::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->select('id', 'name')->get();
        return AssetsCategoriesResource::collection($data);
    }

    public function show(AssetsCategory $category)
    {
        return new SingleResource($category);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        return (new AssetsCategoryStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param AssetsCategory $category
     * @return JsonResponse
     */
    public function update(Request $request, AssetsCategory $category): JsonResponse
    {
        return (new AssetsCategoryUpdatingService($category))->update($request);
    }

    /**
     * @param AssetsCategory $category
     * @return JsonResponse
     */
    public function destroy(AssetsCategory $category): JsonResponse
    {
        return (new AssetsCategoryDeletingService($category))->delete();
    }

    public function importAssetsCategories(Request $request)
    {
        return (new ImportBuilder())
            ->file($request->file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return AssetsCategory::create($item);
            })
            ->import();
    }

    public function exportAssetsCategories(Request $request)
    {
        $asset_types = QueryBuilder::for(AssetsCategory::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Asset Categories" => ExportBuilder::generator($asset_types)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Asset Categories')->build();
    }
}
