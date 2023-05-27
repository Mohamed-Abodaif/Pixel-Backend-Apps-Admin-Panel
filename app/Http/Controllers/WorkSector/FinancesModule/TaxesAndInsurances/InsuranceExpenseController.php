<?php

namespace App\Http\Controllers\WorkSector\FinancesModule\TaxesAndInsurances;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\FinanceModule\TaxesAndInsurances\InsuranceExpense;
use App\Http\Requests\WorkSector\FinancesModule\TaxesAndInsurances\InsuranceExpenseRequest;
use App\Http\Resources\WorkSector\FinancesModule\TaxesAndInsurances\InsuranceExpenseResource;

class InsuranceExpenseController extends Controller
{

    /* function list(Request $request) {
    $data = InsuranceExpense::with([
        'tender:name',
        'purchaseInvoice:purchase_invoice_name',
        'currency:name,symbol',
        'paymentMethod:name',
        'clientOrder:order_name',
        'asset:asset_name',
        'client:name'
    ])->get();
    return InsuranceExpenseResource::collection($data);
} */

    // public function __construct()
    // {
    //     $this->middleware('permission:read_exm-taxes-and-insurances')->only(['index']);
    //     $this->middleware('permission:create_exm-taxes-and-insurances')->only(['store']);
    //     $this->middleware('permission:read_exm-taxes-and-insurances')->only(['show']);
    //     $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
    //     $this->middleware('permission:delete_exm-taxes-and-insurances')->only(['destroy']);
    //     $this->middleware('permission:import_exm-taxes-and-insurances')->only(['importInsurance']);
    //     $this->middleware('permission:export_exm-taxes-and-insurances')->only(['exportInsurnce']);
    // }
    public function index(Request $request)
    {

        $data = QueryBuilder::for(InsuranceExpense::class)->createdBy()->with([
            'tender:id,name',
            'purchaseInvoice:purchase_invoice_name',
            'currency:id,name,symbol',
            'paymentMethod:name,id',
            'clientOrder:order_name,id',
            'asset:asset_name,id',
            'client:name,id'
        ])
            ->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(InsuranceExpense::class, $request, 'insurances');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    function list(Request $request)
    {
        $data = QueryBuilder::for(InsuranceExpense::class)
            ->allowedFilters(['payment_date'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'payment_date']);
        return InsuranceExpenseResource::collection($data);
    }

    public function store(InsuranceExpenseRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        InsuranceExpense::create($data);
        $response = [
            "message" => "Created Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function update(InsuranceExpenseRequest $request, $id)
    {
        $data = $request->all();

        $expens_type = InsuranceExpense::findOrFail($id);
        $expens_type->update($data);

        $response = [
            "message" => "Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $expens_type = InsuranceExpense::findOrFail($id);
        $expens_type->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $item = InsuranceExpense::with([
            'tender:name',
            'purchaseInvoice:purchase_invoice_name',
            'currency:name,symbol',
            'paymentMethod:name',
            'clientOrder:order_name',
            'asset:asset_name',
            'client:name'
        ])->findOrFail($id);
        return new SingleResource($item);
    }
    public function importInsurance(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return InsuranceExpense::create($item);
            })
            ->import();
    }

    public function exportInsurnce(Request $request)
    {
        $insurances = QueryBuilder::for(InsuranceCategory::class)->createdBy()->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Insurances" => ExportBuilder::generator($insurances)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Exchange')->build();
    }

    public function duplicate(InsuranceExpenseRequest $request, $id)
    {
        $data = $request->all();
        $recored = InsuranceExpense::find($id);
        $copy = $recored->replicate()->fill($data);
        $copy->save();

        $response = [
            "message" => "duplicated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }
    private function filters()
    {
        return [
            'created_at',
            'payment_date',
            'attachments',
            'notes',
            'amount',
            'paid_to',
            'insurance_duration',
            'months_list',
            'years_list',
            'insurance_refrence_number',
            'tender_insurance_percentage',
            'tender_estimated_date',
            'final_refund_date',
            'category',
            'type',
            'tender_insurance_type',
            'client.name',
            'asset.asset_name',
            'tender.name',
            'currency.name',
            'purchaseInvoice.purchase_invoice_name',
            'clientOrder.order_name',
            'paymentMethod.name'
        ];
    }
}
