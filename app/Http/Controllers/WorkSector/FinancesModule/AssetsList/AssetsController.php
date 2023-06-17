<?php

namespace App\Http\Controllers\WorkSector\FinancesModule\AssetsList;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\FinanceModule\AssetsList\Asset;
use App\Http\Requests\WorkSector\FinancesModule\AssetsList\AssetsRequest;
use App\Http\Resources\WorkSector\FinancesModule\AssetsList\AssetsResource;
use App\Services\WorkSector\FinancesModule\AssetsListService\AssetStoringService;
use App\Services\WorkSector\FinancesModule\AssetsListService\AssetUpdatingService;
use Illuminate\Http\JsonResponse;

class AssetsController extends Controller
{

    protected $filterable = [
        'asset_name',
        'created_at',
        'buying_date',
        'assetCategory.name',
        'asset_description',
        'asset_documents',
        'department.name',
        'notes',
        'status'
    ];

    public function __construct()
    {
        // $this->middleware('permission:read_sfm-assets-list')->only(['index']);
        // $this->middleware('permission:create_sfm-assets-list')->only(['store']);
        // $this->middleware('permission:read_sfm-assets-list')->only(['show']);
        // $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
        // $this->middleware('permission:delete_sfm-assets-list')->only(['destroy']);
        // $this->middleware('permission:import_sfm-assets-list')->only(['importTaxTypes']);
        // $this->middleware('permission:export_sfm-assets-list')->only(['importAssets']);
    }

    public function list(Request $request)
    {
        $data = QueryBuilder::for(Asset::class)
            ->allowedFilters(['asset_name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'asset_name as name']);
        return AssetsResource::collection($data);
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Asset::class)->with(['assetCategory', 'department'])->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Asset::class, $request, 'assets');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    public function store(Request $request)
    {
        $request['order_number'] = 'VO-34343';

        return (new AssetStoringService())->create($request);
    }

    public function update(Request $request, Asset $vendorOrder): JsonResponse
    {
        return (new AssetUpdatingService($vendorOrder))->update($request);
    }

    public function  destroy(Asset $vendor): JsonResponse
    {
        return (new AssetDeletingService($vendor))->delete();
    }

    public function show($id)
    {
        $item = Asset::with(['department', 'assetCategory'])->findOrFail($id);
        return new SingleResource($item);
    }
    public function importAssets(ImportFile $import)
    {
        $file = $import->file;
        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Asset::create($item);
            })
            ->import();
    }

    public function exportAssets(Request $request)
    {
        $assets = QueryBuilder::for(Asset::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Assets" => ExportBuilder::generator($assets)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['asset_name'], 'Status' => $item['status']])
            ->name('Assets')->build();
    }

    public function duplicate(AssetsRequest $request, $id)
    {
        $data = $request->all();
        $recored = Asset::find($id);
        $copy = $recored->replicate()->fill($data);
        $copy->save();
        $response = [
            "message" => "duplicated Successfully",
            "status" => "success",
            "data" => $copy
        ];
        return response()->json($response, 200);
    }

    public function changeStatus(Request $request, $id)
    {
        $status = $request->status;
        Asset::where('id', $id)->update(['status' => $status]);
        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }
}
