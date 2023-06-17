<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Http\Resources\WorkSector\HRModule\ExpenseResource;
use App\Models\WorkSector\SystemConfigurationModels\Expense;
use App\Http\Requests\WorkSector\SystemConfigurations\ExpenseTypes\ExpenseRequest;

class MainExpenseController extends Controller
{

    // protected $filterable = [
    //     "payment_date",
    //     "attachments",
    //     "purchaseInvoice.purchase_invoice_name",
    //     "notes",
    //     "amount",
    //     "paid_to",
    //     "expenseType.name",
    //     "currency.name",
    //     "paymentMethod.name",
    //     "category",
    //     "asset.asset_name",
    //     "client.name",
    //     "clientOrder.order_name",
    // ];

    // public function __construct()
    // {
    //     $this->middleware('permission:read_exm-taxes-and-insurances')->only(['index']);
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
            ->allowedFilters(['payment_date'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'payment_date']);
        return ExpenseResource::collection($data);
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Expense::class)->createdBy()->with(['expenseType', 'purchaseInvoice', 'currency', 'paymentMethod', 'clientOrder', 'asset', 'client'])->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Expense::class, $request, 'expenses');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }


    public function store(ExpenseRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        Expense::create($data);

        $response = [
            "message" => "Created Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function update(ExpenseRequest $request, $id)
    {
        $data = $request->all();

        $expens_type = Expense::findOrFail($id);
        $expens_type->update($data);

        $response = [
            "message" => "Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $expens_type = Expense::findOrFail($id);
        $expens_type->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $item = Expense::with(['expenseType', 'purchaseInvoice', 'currency', 'paymentMethod', 'clientOrder', 'asset', 'client'])->findOrFail($id);
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
        $Exchange = QueryBuilder::for(Expense::class)->createdBy()->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Exchanges" => ExportBuilder::generator($Exchange)
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
            "status" => "success"
        ];
        return response()->json($response, 200);
    }


    private function filters(): array
    {
        return [
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
            AllowedFilter::exact("status")
        ];
    }

    public function editRequestMessage($id)
    {

        $expense = Expense::findOrFail($id);

        if ($expense->editRequests->count() > 0) {
            $message = $expense->editRequests->first()->required_edit;
        }

        $response = [
            "message" => "retrieved Successfully",
            "data" => ['required_edit' => $message],
            "status" => "success"
        ];
        return response()->json($response, 200);
    }
}
