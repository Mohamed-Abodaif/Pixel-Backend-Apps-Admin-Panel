<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\CustodySender;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\CustodySenderResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CustodySendersOperations\CustodySenderStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CustodySendersOperations\CustodySenderDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CustodySendersOperations\CustodySenderUpdatingService;

class CustodySendersController extends Controller
{

    protected $filterable = [
        'name',
        'status',
        'user.name'
    ];

    public function __construct()
    {
        // $this->middleware('permission:read_sc-dropdown-lists')->only(['index']);
        // $this->middleware('permission:create_sc-dropdown-lists')->only(['store']);
        // $this->middleware('permission:read_sc-dropdown-lists')->only(['show']);
        // $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
        // $this->middleware('permission:delete_sc-dropdown-lists')->only(['destroy']);
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importCustodySenders']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportCustodySenders']);

    }
    //
    public function index(Request $request)
    {
        $data = QueryBuilder::for(CustodySender::class)
            ->allowedIncludes('user:id,name')
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    function list()
    {
        $data = QueryBuilder::for(CustodySender::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return CustodySenderResource::collection($data);
    }

    public function store(Request $request): JsonResponse
    {
        return (new CustodySenderStoringService())->create($request);
    }

    public function update(Request $request, CustodySender $sender): JsonResponse
    {
        return (new CustodySenderUpdatingService($sender))->update($request);
    }

    public function show(CustodySender $sender)
    {
        return new SingleResource($sender);
    }


    public function destroy(CustodySender $sender): JsonResponse
    {
        return (new CustodySenderDeletingService($sender))->delete();
    }

    public function importCustodySenders(Request $import)
    {
        $file = $import->file;
        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return CustodySender::create($item);
            })
            ->import();
    }

    public function exportCustodySenders(Request $request)
    {
        $taxes = QueryBuilder::for(CustodySender::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Custody Senders" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Custody Senders')->build();
    }
}
