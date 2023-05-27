<?php

namespace App\Http\Controllers\WorkSector\FinancesModule\SalesInvoices;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\FinanceModule\SalesInvoices\SalesInvoice;
use App\Http\Requests\WorkSector\FinancesModule\SalesInvoices\SaleInvoiceRequest;
use App\Http\Resources\WorkSector\FinancesModule\SalesInvoices\SaleInvoiceResource;

class SaleInvoicesController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('permission:read_sfm-sales-invoices')->only(['index']);
    //     $this->middleware('permission:create_sfm-sales-invoices')->only(['store']);
    //     $this->middleware('permission:read_sfm-sales-invoices')->only(['show']);
    //     $this->middleware('permission:edit_sfm-sales-invoices')->only(['update']);
    //     $this->middleware('permission:delete_sfm-sales-invoices')->only(['destroy']);
    //     $this->middleware('permission:import_sfm-sales-invoices')->only(['importSalesInvoices']);
    //     $this->middleware('permission:export_sfm-sales-invoices')->only(['exportSalesInvoices']);
    // }

    function list(Request $request)
    {
        $data = QueryBuilder::for(SalesInvoice::class)
            ->allowedFilters(['sales_invoice_number'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'sale_invoice_name as name']);
        return SaleInvoiceResource::collection($data);
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(SalesInvoice::class)->with(['client', 'department', 'currency', 'payments'])->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(SalesInvoice::class, $request, 'sales_invoices');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    public function store(SaleInvoiceRequest $request)
    {
        $data = $request->safe()->all();
        $invoice = new SalesInvoice($data);
        #TODO
        //calculate net value;
        $invoice->rest_value = (float) $invoice->invoice_net_value;
        $invoice->save();
        $response = [
            "message" => "Created Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function update($id, SaleInvoiceRequest $request)
    {
        $data = $request->safe()->all();
        $purchaseInvoice = SalesInvoice::findOrFail($id);
        $purchaseInvoice->update($data);
        $response = [
            "message" => "Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $purchaseInvoice = SalesInvoice::findOrFail($id);
        $purchaseInvoice->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }


    public function show($id)
    {
        $item = SalesInvoice::with([
            'client',
            'department',
            'currency'
        ])->findOrFail($id);
        return new SingleResource($item);
    }

    public function importSalesInvoices(Request $request)
    {
        return (new ImportBuilder())
            ->file($request->file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return SalesInvoice::create($item);
            })
            ->import();
    }

    public function exportSalesInvoices(Request $request)
    {
        $taxes = QueryBuilder::for(SalesInvoice::class)->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Sales Invoices" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Sales Invoices')->build();
    }

    public function duplicate(SaleInvoiceRequest $request, $id)
    {
        $data = $request->all();
        $recored = SalesInvoice::find($id);
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
        SalesInvoice::where('id', $id)->update(['status' => $status]);

        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }

    private function filters(): array
    {
        return [
            'created_at',
            'client.name',
            'department.name',
            'notes',
            'status',
            'invoice_net_value',
            AllowedFilter::exact("invoice_status"),
            AllowedFilter::exact("payment_status"),
            'rest_value',
            'refunded_value',
            'paid_value',
            AllowedFilter::callback('sales_invoice_number', function (Builder $query, $value) {
                $query->where('sales_invoice_number', 'LIKE', "%{$value}%")
                    ->orWhere('electronic_sales_invoice_number', 'LIKE', "%{$value}%");
            }),
        ];
    }
}