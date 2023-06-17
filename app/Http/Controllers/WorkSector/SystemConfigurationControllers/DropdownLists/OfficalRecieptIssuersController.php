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
use App\Models\WorkSector\SystemConfigurationModels\OfficalRecieptIssuer;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\OfficalRecieptIssuerResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\OfficalRecieptIssuersOperations\OfficalRecieptIssuerStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\OfficalRecieptIssuersOperations\OfficalRecieptIssuerDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\OfficalRecieptIssuersOperations\OfficalRecieptIssuerUpdatingService;

class OfficalRecieptIssuersController extends Controller
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
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importOfficalRecieptIssuers']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportOfficalRecieptIssuers']);

    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(OfficalRecieptIssuer::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    public function show(OfficalRecieptIssuer $officalReceiptIssuers)
    {
        return new SingleResource($officalReceiptIssuers);
    }

    function list()
    {
        $data = QueryBuilder::for(OfficalRecieptIssuer::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return OfficalRecieptIssuerResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        return (new OfficalRecieptIssuerStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param OfficalRecieptIssuer $officalReceiptIssuers
     * @return JsonResponse
     */
    public function update(Request $request, OfficalRecieptIssuer $officalReceiptIssuers): JsonResponse
    {
        return (new OfficalRecieptIssuerUpdatingService($officalReceiptIssuers))->update($request);
    }

    /**
     * @param OfficalRecieptIssuer $officalReceiptIssuers
     * @return JsonResponse
     */
    public function destroy(OfficalRecieptIssuer $officalReceiptIssuers): JsonResponse
    {
        return (new OfficalRecieptIssuerDeletingService($officalReceiptIssuers))->delete();
    }

    public function importOfficalRecieptIssuers(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return OfficalRecieptIssuer::create($item);
            })
            ->import();
    }

    public function exportOfficalRecieptIssuers(Request $request)
    {
        $taxes = QueryBuilder::for(OfficalRecieptIssuer::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "OfficalRecieptIssuers" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('OfficalRecieptIssuers')->build();
    }
}
