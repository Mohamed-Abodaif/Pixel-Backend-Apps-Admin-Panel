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
use App\Models\WorkSector\SystemConfigurationModels\CompanyTreasury;
use App\Http\Resources\WorkSector\SystemConfigurationResources\CompanyTreasuryResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyTreasuriesOperations\CompanyTreasuryDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyTreasuriesOperations\CompanyTreasuryStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyTreasuriesOperations\CompanyTreasuryUpdatingService;

class CompanyTreasuriesController extends Controller
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
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importCompanyTreasurys']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportCompanyTreasurys']);

    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(CompanyTreasury::class)->with(['branch','person','currency'])
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    public function show(CompanyTreasury $companyTreasury)
    {
        return new SingleResource($companyTreasury);
    }

    function list()
    {
        $data = QueryBuilder::for(CompanyTreasury::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return CompanyTreasuryResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        return (new CompanyTreasuryStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param CompanyTreasury $companyTreasury
     * @return JsonResponse
     */
    public function update(Request $request, CompanyTreasury $companyTreasury): JsonResponse
    {
        return (new CompanyTreasuryUpdatingService($companyTreasury))->update($request);
    }

    /**
     * @param CompanyTreasury $companyTreasury
     * @return JsonResponse
     */
    public function destroy(CompanyTreasury $companyTreasury): JsonResponse
    {
        return (new CompanyTreasuryDeletingService($companyTreasury))->delete();
    }

    public function importCompanyTreasurys(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return CompanyTreasury::create($item);
            })
            ->import();
    }

    public function exportCompanyTreasurys(Request $request)
    {
        $taxes = QueryBuilder::for(CompanyTreasury::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "CompanyTreasuries" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('CompanyTreasuries')->build();
    }
}
