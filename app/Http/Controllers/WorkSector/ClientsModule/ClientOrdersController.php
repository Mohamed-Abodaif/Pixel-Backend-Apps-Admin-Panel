<?php

namespace App\Http\Controllers\WorkSector\ClientsModule;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\ClientsModule\ClientOrder;
use App\Http\Requests\WorkSector\ClientsModule\ClientOrderRequest;
use App\Http\Resources\WorkSector\ClientsModule\ClientOrderResource;
use App\Services\WorkSector\ClientsModule\ClientOrderServices\ClientOrderDeletingService;
use App\Services\WorkSector\ClientsModule\ClientOrderServices\ClientOrderStoringService;
use App\Services\WorkSector\ClientsModule\ClientOrderServices\ClientOrderUpdatingService;

class ClientOrdersController extends Controller
{


    public function __construct()
    {
        // $this->middleware('permission:read_cmm-clients-orders')->only(['index']);
        // $this->middleware('permission:create_cmm-clients-orders')->only(['store']);
        // $this->middleware('permission:read_cmm-clients-orders')->only(['show']);
        // $this->middleware('permission:edit_cmm-clients-orders')->only(['update']);
        // $this->middleware('permission:delete_cmm-clients-orders')->only(['destroy']);
        // $this->middleware('permission:import_cmm-clients-orders')->only(['importClientsOrders']);
        // $this->middleware('permission:export_cmm-clients-orders')->only(['exportClientsOrders']);
    }
    //
    public function index(Request $request)
    {
        $status = $request->filter['status'] ?? 'In Progress';

        $data = QueryBuilder::for(ClientOrder::class)->with(['client', 'department', 'currency', 'paymentTerm'])->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        // $statistics = $this->statistics(ClientOrder::class, $request, 'clients_orders');
        $statistics = $this->statistics(ClientOrder::class, $request, 'clients_orders', $this->getStatisticsFilter($status));

        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    public function list(Request $request)
    {
        $data = QueryBuilder::for(ClientOrder::class)->allowedFilters([
            AllowedFilter::callback('order_name', function (Builder $query, $value) {
                $query->where('order_name', 'LIKE', "%$value%")
                    ->orWhere('order_number', 'LIKE', "%$value%");
            }),
        ])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'order_name as name']);
        return ClientOrderResource::collection($data);
    }


    public function store(Request $request)
    {
        $request['po_net_value'] = $request['po_total_value'] + $request['po_sales_taxes_value'] - $request['po_add_and_discount_taxes_value'];

        return (new ClientOrderStoringService())->create($request);
    }

    public function update(Request $request, ClientOrder $clientOrder): JsonResponse
    {
        return (new ClientOrderUpdatingService($clientOrder))->update($request);
    }

    public function  destroy(ClientOrder $client): JsonResponse
    {
        return (new ClientOrderDeletingService($client))->delete();
    }


    public function show($id)
    {
        $item = ClientOrder::with('client', 'currency', 'paymentTerm', 'department')->findOrFail($id);
        return new SingleResource($item);
    }

   
    public function importClientsOrders(ImportFile $import)
    {
        $file = $import->file;
        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return ClientOrder::create($item);
            })
            ->import();
    }

    public function exportClientsOrders(Request $request)
    {
        $asset_types = QueryBuilder::for(ClientOrder::class)->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Client Orders" => ExportBuilder::generator($asset_types)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Client Orders')->build();
    }

    public function duplicate(ClientOrderRequest $request, $id)
    {
        $data = $request->all();
        $recored = ClientOrder::find($id);
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
        ClientOrder::where('id', $id)->update(['status' => $status]);
        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }

    private function filters(): array
    {
        return  [
            'client.name',
            'date',
            'delivery_date',
            'created_at',
            AllowedFilter::callback('order_name', function (Builder $query, $value) {
                $query->where('order_name', 'LIKE', "%$value%")
                    ->orWhere('order_number', 'LIKE', "%$value%");
            }),
            'po_total_value',
            'department.name',
            'paymentTerm.name',
            'currency.name',
            'po_attachments',
            'notes',
            'status',
        ];
    }

    /**
     * @param mixed $status
     *
     * @return array
     */
    private function getStatisticsFilter(?string $status): array
    {
        return [
            'where' => [
                ['client_orders.status', $status],
            ],
            'whereNull' => [
                //  ['currencies.deleted_at'],
                ['client_orders.deleted_at']
            ],
        ];
    }
}
