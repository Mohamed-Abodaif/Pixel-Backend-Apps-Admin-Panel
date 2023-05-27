<?php

namespace App\Http\Controllers\PersonalSector\PersonalTransactions\OutFlow;

use ExportBuilder;
use App\Export\Exportable;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Requests\ImportFile;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\ExchangeExpense;
use App\Http\Requests\WorkSector\SystemConfigurationsRequests\ExpenseTypes\ExchangeExpenseRequest;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\ExchangeExpenseResource;

class ExchangeExpenseController extends Controller
{

    protected $filterable = [
        'exchange_date',
        'created_at',
        'from_amount',
        'to_amount',
        'exchange_place',
        'expenseType.name',
        'paymentMethod.name',
        'notes'
    ];

    // public function __construct()
    // {
    //     $this->middleware('permission:read_exm-taxes-and-insurances')->only(['index']);
    //     $this->middleware('permission:create_exm-taxes-and-insurances')->only(['store']);
    //     $this->middleware('permission:read_exm-taxes-and-insurances')->only(['show']);
    //     $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
    //     $this->middleware('permission:delete_exm-taxes-and-insurances')->only(['destroy']);
    //     $this->middleware('permission:import_exm-taxes-and-insurances')->only(['importExchange']);
    //     $this->middleware('permission:export_exm-taxes-and-insurances')->only(['exportExchange']);
    // }

    function list(Request $request)
    {
        $data = QueryBuilder::for(ExchangeExpense::class)
            ->allowedFilters(['exchange_date'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'exchange_date']);
        return ExchangeExpenseResource::collection($data);
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(ExchangeExpense::class)->createdBy()->with(['paymentMethod:id,name', 'fromCurrency:id,name,symbol', 'toCurrency:id,name,symbol'])->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(ExchangeExpense::class, $request, 'currency_exchanges');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }


    public function store(ExchangeExpenseRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        #TODO
        //calculate rate
        $data['exchange_rate'] = 1;
        ExchangeExpense::create($data);
        $response = [
            "message" => "Created Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function update(ExchangeExpenseRequest $request, $id)
    {
        $data = $request->all();

        $expens_type = ExchangeExpense::findOrFail($id);
        $expens_type->update($data);

        $response = [
            "message" => "Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $expens_type = ExchangeExpense::findOrFail($id);
        $expens_type->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $item = ExchangeExpense::with(['paymentMethod', 'fromCurrency', 'toCurrency'])->findOrFail($id);
        return new SingleResource($item);
    }
    public function importExchange(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return ExchangeExpense::create($item);
            })
            ->import();
    }

    public function exportExchange(Request $request)
    {
        $Exchange = QueryBuilder::for(ExchangeExpense::class)->createdBy()->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Exchanges" => ExportBuilder::generator($Exchange)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Exchange')->build();
    }

    public function duplicate(ExchangeExpenseRequest $request, $id)
    {
        $data = $request->all();
        $recored = ExchangeExpense::find($id);
        $copy = $recored->replicate()->fill($data);
        $copy->save();

        $response = [
            "message" => "duplicated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }
}
