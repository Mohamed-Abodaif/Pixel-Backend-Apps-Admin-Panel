<?php

namespace App\Http\Controllers\PersonalSector\PersonalTransactions\OutFlow;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Http\Resources\WorkSector\HRModule\ExpenseResource;
use App\Http\Requests\WorkSector\SystemConfigurations\ExpenseTypes\ExpenseRequest;
use App\Models\PersonalSector\PersonalTransactions\Outflow\Expense;
use App\Services\PersonalSector\PersonalTransactions\OutFlow\ExpenseesServices\ExpenseDeletingService;
use App\Services\PersonalSector\PersonalTransactions\OutFlow\ExpenseesServices\ExpenseStoringService;
use App\Services\PersonalSector\PersonalTransactions\OutFlow\ExpenseesServices\ExpenseUpdatingService;
use Illuminate\Http\JsonResponse;

class ExpensesController extends Controller
{

    protected $filterable = [
        "created_at",
        "payment_date",
        "attachments",
        "purchaseInvoice.name",
        "notes",
        "amount",
        "paid_to",
        "duration",
        "expenseType.name",
        "currency.name",
        "paymentMethod.name",
        "category",
        "asset.asset_name",
        "client.name",
        "clientOrder.order_name",
        "status"
    ];

    // public function __construct()
    // {
    //     $this->middleware('permission:read_exm-taxes-and-insun rances')->only(['index']);
    //     $this->middleware('permission:create_exm-taxes-and-insurances')->only(['store']);
    //     $this->middleware('permission:read_exm-taxes-and-insurances')->only(['show']);
    //     $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
    //     $this->middleware('permission:delete_exm-taxes-and-insurances')->only(['destroy']);
    //     $this->middleware('permission:import_exm-taxes-and-insurances')->only(['importExpense']);
    //     $this->middleware('permission:export_exm-taxes-and-insurances')->only(['exportExpense']);
    // }

    function list(Request $request)
    {
        $data =  QueryBuilder::for(Expense::class)
            //->allowedFilters(['payment_date'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'payment_date']);
        return ExpenseResource::collection($data);
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Expense::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Expense::class, $request, 'expenses');

        return response()->success([
            'list' => $data,
            'statistics' => $statistics,
        ]);
    }

    public function store(Request $request)
    {
        $request['user_id'] = auth()->user()->id;

        return (new ExpenseStoringService())->create($request);
    }

    public function update(Request $request, Expense $expense): JsonResponse
    {
        return (new ExpenseUpdatingService($expense))->update($request);
    }

    public function  destroy(Expense $expense): JsonResponse
    {
        return (new ExpenseDeletingService($expense))->delete();
    }

    public function show($id)
    {
        $item = Expense::findOrFail($id);
        return new ExpenseResource($item);
    }
    public function importExpense(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Expense::create($item);
            })
            ->import();
    }

    public function exportExpense(Request $request)
    {
        $Exchange = QueryBuilder::for(Expense::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();

        $list = new SheetCollection([
            "Exchanges" => ExportBuilder::generator($Exchange),
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Exchange')->build();
    }

    public function duplicate(ExpenseRequest $request, $id)
    {
        $data = $request->all();
        $recored = Expense::find($id);
        $copy = $recored->replicate()->fill($data);
        $copy->save();

        $response = [
            "message" => "duplicated Successfully",
            "status" => "success",
        ];
        return response()->json($response, 200);
    }
}
