<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\Tender;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\TendersResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TendersOperations\TenderStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TendersOperations\TenderDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TendersOperations\TenderUpdatingService;

class TendersController extends Controller
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
    //     $this->middleware('permission:import_sc-dropdown-lists')->only(['importTenders']);
    //     $this->middleware('permission:export_sc-dropdown-lists')->only(['exportTenders']);
    // }
    //
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Tender::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);
        return Response::success(['list' => $data]);
    }

    public function list(Request $request)
    {
        $data = QueryBuilder::for(Tender::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return TendersResource::collection($data);
    }

    public function show(Tender $tender)
    {
        return new SingleResource($tender);
    }

    public function store(Request $request): JsonResponse
    {
        return (new TenderStoringService())->create($request);
    }

    public function update(Request $request, Tender $tender): JsonResponse
    {
        return (new TenderUpdatingService($tender))->update($request);
    }

    public function destroy(Tender $tender): JsonResponse
    {
        return (new TenderDeletingService($tender))->delete();
    }

    public function importTenders(Request $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Tender::create($item);
            })
            ->import();
    }

    public function exportTenders(Request $request)
    {
        $taxes = QueryBuilder::for(Tender::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Tenders" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Tenders')->build();
    }
}
