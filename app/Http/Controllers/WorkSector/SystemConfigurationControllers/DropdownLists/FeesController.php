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
use App\Models\WorkSector\SystemConfigurationModels\Fees;
use App\Http\Requests\WorkSector\SystemConfigurations\Fees\StoringFeeRequest;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\FeesResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\FeesOperations\FeesStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\FeesOperations\FeesUpdatingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\FeesOperations\FeesDeletingService;

class FeesController extends Controller
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
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importFeess']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportFeess']);

    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(Fees::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    public function show(Fees $taxesOfficialFees)
    {
        return new SingleResource($taxesOfficialFees);
    }

    function list()
    {
        $data = QueryBuilder::for(Fees::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return FeesResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        return (new FeesStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param Fees $taxesOfficialFees
     * @return JsonResponse
     */
    public function update(Request $request, Fees $taxesOfficialFees): JsonResponse
    {
        return (new FeesUpdatingService($taxesOfficialFees))->update($request);
    }

    /**
     * @param Fees $taxesOfficialFees
     * @return JsonResponse
     */
    public function destroy(Fees $taxesOfficialFees): JsonResponse
    {
        return (new FeesDeletingService($taxesOfficialFees))->delete();
    }

    public function importFeess(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Fees::create($item);
            })
            ->import();
    }

    public function exportFeess(Request $request)
    {
        $taxes = QueryBuilder::for(Fees::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Feess" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Feess')->build();
    }
}
