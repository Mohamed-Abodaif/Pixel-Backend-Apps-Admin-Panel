<?php

namespace App\Http\Controllers\WorkSector\FinancesModule\PurchaseInvoices;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\FinanceModule\PurchaseInvoices\PurchaseInvoice;
use App\Http\Requests\WorkSector\FinancesModule\PurchaseInvoices\PurchaseInvoiceRequest;
use App\Http\Resources\WorkSector\FinancesModule\PurchaseInvoices\PurchaseInvoiceResource;

class PurchaseInvoicesController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('permission:read_vmm-purchase-invoices')->only(['index']);
    //     $this->middleware('permission:create_vmm-purchase-invoices')->only(['store']);
    //     $this->middleware('permission:read_vmm-purchase-invoices')->only(['show']);
    //     $this->middleware('permission:edit_vmm-purchase-invoices')->only(['update']);
    //     $this->middleware('permission:delete_vmm-purchase-invoices')->only(['destroy']);
    //     $this->middleware('permission:import_vmm-purchase-invoices')->only(['importPurchaseInvoices']);
    //     $this->middleware('permission:export_vmm-purchase-invoices')->only(['exportPurchaseInvoices']);
    // }

    public function list(Request $request)
    {
        $data = QueryBuilder::for(PurchaseInvoice::class)
            ->allowedFilters(['purchase_invoice_name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'purchase_invoice_name AS name']);
        return PurchaseInvoiceResource::collection($data);
    }


    public function index(Request $request)
    {
        $data = QueryBuilder::for(PurchaseInvoice::class)->with(['vendor', 'department', 'currency'])->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);

        $statistics = $this->statistics(PurchaseInvoice::class, $request, 'purchase_invoices');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    public function statisticClients($period = 'all-time', $from = null, $to = null)
    {
        return PurchaseInvoice::getCalculation('PurchaseInvoices', $period, $from, $to);
    }
    public function show($id)
    {
        $item = PurchaseInvoice::with([
            'vendor',
            'department',
            'currency'
        ])->findOrFail($id);
        return new SingleResource($item);
    }

    public function store(PurchaseInvoiceRequest $request)
    {
        $data = $request->all();
        $data['vendor_purchase_invoice_number'] = "PRINV-89384389";
        $data['purchase_invoice_name'] = "PRINVNAME-89384389";
        PurchaseInvoice::create($data);
        $response = [
            "message" => "Created Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function update($id, PurchaseInvoiceRequest $request)
    {
        $data = $request->all();
        $purchaseInvoice = PurchaseInvoice::findOrFail($id);
        $purchaseInvoice->update($data);
        $response = [
            "message" => "Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $purchaseInvoice = PurchaseInvoice::findOrFail($id);
        $purchaseInvoice->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function importPurchaseInvoices(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return PurchaseInvoice::create($item);
            })
            ->import();
    }

    public function exportPurchaseInvoices(Request $request)
    {
        $taxes = QueryBuilder::for(PurchaseInvoice::class)->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Purchase Invoices" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Purchase Invoices')->build();
    }

    public function duplicate(PurchaseInvoiceRequest $request, $id)
    {
        $data = $request->all();
        $data['vendor_purchase_invoice_number'] = "PRINV-89384389";
        $recored = PurchaseInvoice::find($id);
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
        PurchaseInvoice::where('id', $id)->update(['status' => $status]);

        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }

    private function filters()
    {
        return [
            'date',
            'created_at',
            'vendor.name',
            'department.name',
            'notes',
            'status',
            'currency.name',
            'invoice_net_value',
            AllowedFilter::callback('electronic_purchase_invoice_number', function (Builder $query, $value) {
                $query->where('electronic_purchase_invoice_number', 'LIKE', "%{$value}%")
                    ->orWhere('vendor_purchase_invoice_number', 'LIKE', "%{$value}%");
            })
        ];
    }
}
