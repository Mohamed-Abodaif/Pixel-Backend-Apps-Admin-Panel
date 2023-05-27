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
use App\Models\WorkSector\ClientsModule\ClientQuotation;
use App\Http\Requests\WorkSector\ClientsModule\ClientQuotationRequest;
use App\Http\Resources\WorkSector\ClientsModule\ClientQuotationResource;
use App\Services\WorkSector\ClientsModule\ClientQuotationServices\ClientQuotationDeletingService;
use App\Services\WorkSector\ClientsModule\ClientQuotationServices\ClientQuotationStoringService;
use App\Services\WorkSector\ClientsModule\ClientQuotationServices\ClientQuotationUpdatingService;

class ClientQuotationsController extends Controller
{

    public function __construct()
    {
        // $this->middleware('permission:read_cmm-clients-quotations')->only(['index']);
        // $this->middleware('permission:create_cmm-clients-quotations')->only(['store']);
        // $this->middleware('permission:read_cmm-clients-quotations')->only(['show']);
        // $this->middleware('permission:edit_cmm-clients-quotations')->only(['update']);
        // $this->middleware('permission:delete_cmm-clients-quotations')->only(['destroy']);
        // $this->middleware('permission:import_cmm-clients-quotations')->only(['importClientQuotations']);
        // $this->middleware('permission:export_cmm-clients-quotations')->only(['exportClientQuotations']);
    }

    public function list()
    {
        $data = QueryBuilder::for(ClientQuotation::class)->allowedFilters([
            AllowedFilter::callback('quotation_name', function (Builder $query, $value) {
                $query->where('quotation_name', 'LIKE', "%$value%")
                    ->orWhere('quotation_number', 'LIKE', "%$value%");
            }),
        ])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'quotation_number']);
        return ClientQuotationResource::collection($data);
    }


    public function index(Request $request)
    {
        $status = $request->filter['status'] ?? 'Draft';

        $data = QueryBuilder::for(ClientQuotation::class)->with(['client', 'department', 'currency', 'paymentTerm'])->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        // $statistics = $this->statistics(ClientQuotation::class, $request, 'clients_quotations');
        $statistics = $this->statistics(ClientQuotation::class, $request, 'clients_quotations', $this->getStatisticsFilter($status));

        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }



    public function show($id)
    {
        $item = ClientQuotation::with([
            'department', 'currency', 'client', 'paymentTerm'
        ])->findOrFail($id);
        return new SingleResource($item);
    }


    public function store(Request $request)
    {
        $request['quotation_number'] = $this->generateQuotationNumber();


        return (new ClientQuotationStoringService())->create($request);
    }

    public function update(Request $request, ClientQuotation $clientOrder): JsonResponse
    {
        return (new ClientQuotationUpdatingService($clientOrder))->update($request);
    }

    public function  destroy(ClientQuotation $client): JsonResponse
    {
        return (new ClientQuotationDeletingService($client))->delete();
    }


    public function importClientQuotations(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return ClientQuotation::create($item);
            })
            ->import();
    }

    public function exportClientQuotations(Request $request)
    {
        $asset_types = QueryBuilder::for(ClientQuotation::class)->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Client Quotation" => ExportBuilder::generator($asset_types)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Client Quotation')->build();
    }
    public function duplicate(ClientQuotationRequest $request, $id)
    {
        $data = $request->all();
        $recored = ClientQuotation::find($id);
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
        ClientQuotation::where('id', $id)->update(['status' => $status]);

        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }


    private function filters(): array
    {
        return  [
            'date',
            'due_date',
            'created_at',
            'client.name',
            AllowedFilter::callback('quotation_name', function (Builder $query, $value) {
                $query->where('quotation_name', 'LIKE', "%$value%")
                    ->orWhere('quotation_number', 'LIKE', "%$value%");
            }),
            'quotation_net_value',
            'department.name',
            'paymentTerm.name',
            'notes',
            'currency.name',
            'status'
        ];
    }

    public function generateQuotationNumber()
    {
        $code = '#Q_' . random_int(00000, 99999); // better than rand()

        if ($this->codeExists($code)) {
            return $this->generateQuotationNumber();
        }
        return $code;
    }

    function codeExists($code)
    {
        return ClientQuotation::where('quotation_number', $code)->exists();
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
                ['client_quotations.status', $status],
            ],
            'whereNull' => [
                //   ['currencies.deleted_at'],
                ['client_quotations.deleted_at']
            ],
        ];
    }
}
