<?php

namespace App\Http\Controllers\WorkSector\ClientsModule;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\ClientsModule\Client;
use App\Http\Requests\WorkSector\ClientsModule\ClientRequest;
use App\Http\Resources\WorkSector\ClientsModule\ClientResource;
use App\Services\WorkSector\ClientsModule\ClientServices\ClientDeletingService;
use App\Services\WorkSector\ClientsModule\ClientServices\ClientStoringService;
use App\Services\WorkSector\ClientsModule\ClientServices\ClientUpdatingService;

class ClientsController extends Controller
{
    protected $filterable = [
        'name',
        'billing_address',
        'registration_no',
        'taxes_no',
        'type',
        'status',
        'created_at'
    ];

    public function __construct()
    {
        // $this->middleware('permission:read_cmm-clients-list')->only(['index']);
        // $this->middleware('permission:create_cmm-clients-list')->only(['store']);
        // $this->middleware('permission:read_cmm-clients-list')->only(['show']);
        // $this->middleware('permission:edit_cmm-clients-list')->only(['update']);
        // $this->middleware('permission:delete_cmm-clients-list')->only(['destroy']);
        // $this->middleware('permission:import_cmm-clients-list')->only(['importClients']);
        // $this->middleware('permission:export_cmm-clients-list')->only(['exportClients']);
    }

    //
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Client::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Client::class, $request, 'clients');

        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    public function list(Request $request)
    {
        $pageSize = $request->pageSize ?? 10;
        $clients = QueryBuilder::for(Client::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);

        return ClientResource::collection($clients);
    }

    public function store(Request $request)
    {
        return (new ClientStoringService())->create($request);
    }

    public function update(Request $request, Client $client): JsonResponse
    {
        return (new ClientUpdatingService($client))->update($request);
    }

    public function  destroy(Client $client): JsonResponse
    {
        return (new ClientDeletingService($client))->delete();
    }

    public function show($id)
    {
        $item = Client::findOrFail($id);
        return new SingleResource($item);
    }
    public function importClients(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Client::create($item);
            })
            ->import();
    }

    public function exportClients(Request $request)
    {
        $asset_types = QueryBuilder::for(Client::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Clients" => ExportBuilder::generator($asset_types)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Clients')->build();
    }

    public function duplicate(ClientRequest $request, $id)
    {
        $data = $request->all();
        $recored = Client::find($id);
        $copy = $recored->replicate()->fill($data);
        $copy->save();

        $response = [
            "message" => "duplicated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }


    public function changeStatus(Request $request, $id)
    {
        $status = $request->status;

        Client::where('id', $id)->update(['status' => $status]);
        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }
}