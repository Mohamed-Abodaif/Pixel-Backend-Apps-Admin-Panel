<?php

namespace App\Http\Controllers\PersonalSector\PersonalTransactions\Inflow;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\PersonalSector\PersonalTransactions\Inflow\Custody;
use App\Http\Resources\PersonalSector\PersonalTransactions\Inflow\CustodiesResource;
use App\Http\Requests\WorkSector\SystemConfigurationsRequests\CustodySenders\CustodyRequest;
use App\Services\PersonalSector\PersonalTransactions\Inflow\CustodiesServices\CustodyDeletingService;
use App\Services\PersonalSector\PersonalTransactions\Inflow\CustodiesServices\CustodyStoringService;
use App\Services\PersonalSector\PersonalTransactions\Inflow\CustodiesServices\CustodyUpdatingService;

class CustodiesController extends Controller
{
    protected $filterable = [
        'name',
        'currency.name',
        'custodySender.name',
        'created_at',
        'amount',
        'status'
    ];

    public function __construct()
    {
        // $this->middleware('permission:read_personal-sector-custody')->only(['index']);
        // $this->middleware('permission:create_personal-sector-custody')->only(['store']);
        // $this->middleware('permission:read_personal-sector-custody')->only(['show']);
        // $this->middleware('permission:edit_personal-sector-custody')->only(['update']);
        // $this->middleware('permission:delete_personal-sector-custody')->only(['destroy']);
        // $this->middleware('permission:import_cmm-custodys-list')->only(['importCustodies']);
        // $this->middleware('permission:export_cmm-custodys-list')->only(['exportCustodies']);
    }

    function list(Request $request)
    {
        $data = QueryBuilder::for(Custody::class)
            ->allowedFilters(['name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'amount']);
        return CustodiesResource::collection($data);
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Custody::class)->createdBy()->with(['currency', 'custodySender'])->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Custody::class, $request, 'custodies');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    public function show($id)
    {
        $item = Custody::with([
            'currency',
            'custodySender'
        ])->findOrFail($id);

        return new SingleResource($item);
    }

   

    public function store(Request $request)
    {
        $request['user_id'] = auth()->user()->id;

        return (new CustodyStoringService())->create($request);
    }

    public function update(Request $request, Custody $custody): JsonResponse
    {
        return (new CustodyUpdatingService($custody))->update($request);
    }

    public function  destroy(Custody $custody): JsonResponse
    {
        return (new CustodyDeletingService($custody))->delete();
    }
    
    public function importCustodies(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Custody::create($item);
            })
            ->import();
    }

    public function exportCustodies(Request $request)
    {
        $taxes = QueryBuilder::for(Custodies::class)->createdBy()->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Custodies" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Custodies')->build();
    }

    public function duplicate(CustodyRequest $request, $id)
    {
        $data = $request->all();
        $recored = Custody::find($id);
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
        Custody::where('id', $id)->update(['status' => $status]);

        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }
}
