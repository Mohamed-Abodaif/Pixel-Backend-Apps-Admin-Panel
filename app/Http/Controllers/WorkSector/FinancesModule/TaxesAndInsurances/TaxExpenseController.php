<?php

namespace App\Http\Controllers\WorkSector\FinancesModule\TaxesAndInsurances;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\FinanceModule\TaxesAndInsurances\TaxExpense;
use App\Http\Requests\WorkSector\FinancesModule\TaxesAndInsurances\TaxExpenseRequest;

class TaxExpenseController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('permission:read_exm-taxes-and-insurances')->only(['index']);
    //     $this->middleware('permission:create_exm-taxes-and-insurances')->only(['store']);
    //     $this->middleware('permission:read_exm-taxes-and-insurances')->only(['show']);
    //     $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
    //     $this->middleware('permission:delete_exm-taxes-and-insurances')->only(['destroy']);
    //     $this->middleware('permission:import_exm-taxes-and-insurances')->only(['importTaxExpense']);
    //     $this->middleware('permission:export_exm-taxes-and-insurances')->only(['exportTaxExpense']);
    // }


    //
    public function index(Request $request)
    {

        $data = QueryBuilder::for(TaxExpense::class)->createdBy()->with(['currency', 'paymentMethod', 'taxType'])->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(TaxExpense::class, $request, 'taxes');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    /* public function list(Request $request) {
        $data = TaxExpense::with(['currency','paymentMethod','TaxType'])->get();
        return TaxExpenseResource::collection($data);
    } */

    public function show($id)
    {
        $item = TaxExpense::with(['currency', 'paymentMethod', 'taxType'])->findOrFail($id);
        return new SingleResource($item);
    }

    public function store(TaxExpenseRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        TaxExpense::create($data);

        $response = [
            "message" => "Created Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }
    public function update(TaxExpenseRequest $request, $id)
    {
        $data = $request->all();

        $TaxExpense = TaxExpense::findOrFail($id);
        $TaxExpense->update($data);

        $response = [
            "message" => "Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $TaxExpense = TaxExpense::findOrFail($id);
        $TaxExpense->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function importTaxExpense(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return TaxExpense::create($item);
            })
            ->import();
    }

    public function exportTaxExpense(Request $request)
    {
        $taxExpense = QueryBuilder::for(TaxExpense::class)->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Purchase Order Types" => ExportBuilder::generator($taxExpense)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ["No." => $item["id"], "Name" => $item["name"], "Status" => $item["status"]["label"]])
            ->name("Tax Expence")->build();
    }

    private function filters(): array
    {
        return [
            'created_at',
            'payment_date',
            'attachments',
            'notes',
            'amount',
            'paid_to',
            'type',
            'tax_percentage',
            'currency.name',
            'paymentMethod.name',
            'taxType.name'
        ];
    }
}
