<?php

namespace App\Http\Controllers\WorkSector\VendorsModule;


use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\VendorsModule\VendorOrder;
use App\Http\Requests\WorkSector\VendorsModule\VendorOrderRequest;
use App\Http\Resources\WorkSector\VendorsModule\VendorOrderResource;
use App\Services\WorkSector\VendorsModule\VendorOrderServices\VendorOrderDeletingService;
use App\Services\WorkSector\VendorsModule\VendorOrderServices\VendorOrderStoringService;
use App\Services\WorkSector\VendorsModule\VendorOrderServices\VendorOrderUpdatingService;
use Illuminate\Http\JsonResponse;

class VendorOrdersController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('permission:read_cmm-vendors-orders')->only(['index']);
    //     $this->middleware('permission:create_cmm-vendors-orders')->only(['store']);
    //     $this->middleware('permission:read_cmm-vendors-orders')->only(['show']);
    //     $this->middleware('permission:edit_cmm-vendors-orders')->only(['update']);
    //     $this->middleware('permission:delete_cmm-vendors-orders')->only(['destroy']);
    //     $this->middleware('permission:import_cmm-vendors-orders')->only(['importVendorOrders']);
    //     $this->middleware('permission:export_cmm-vendors-orders')->only(['exportVendorOrders']);
    // }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(VendorOrder::class)->with(['vendor', 'department', 'currency', 'paymentTerm'])->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(VendorOrder::class, $request, 'vendors_orders');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }
    public function list(Request $request)
    {
        $data = QueryBuilder::for(VendorOrder::class)
            ->allowedFilters(['order_name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'order_name']);
        return VendorOrderResource::collection($data);
    }

    public function statisticVendors($period = 'all-time', $from = null, $to = null)
    {
        return VendorOrder::getCalculation('Vendors Orders', $period, $from, $to);
    }

    public function show($id)
    {
        $item = VendorOrder::with('vendor', 'currency', 'paymentTerm', 'department')->findOrFail($id);
        return new SingleResource($item);
    }

    public function store(Request $request)
    {
        $request['order_number'] = 'VO-34343';

        return (new VendorOrderStoringService())->create($request);
    }

    public function update(Request $request, VendorOrder $vendorOrder): JsonResponse
    {
        return (new VendorOrderUpdatingService($vendorOrder))->update($request);
    }

    public function  destroy(VendorOrder $vendor): JsonResponse
    {
        return (new VendorOrderDeletingService($vendor))->delete();
    }

    public function importVendorOrders(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return VendorOrder::create($item);
            })
            ->import();
    }

    public function exportVendorOrders(Request $request)
    {
        $taxes = QueryBuilder::for(VendorOrder::class)->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Vendor Orders" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Vendor Orders')->build();
    }

    public function duplicate(VendorOrderRequest $request, $id)
    {
        $data = $request->all();
        $recored = VendorOrder::find($id);
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
        VendorOrder::where('id', $id)->update(['status' => $status]);

        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }

    private function filters()
    {
        return [
            'created_at',
            'vendor.name',
            'date',
            'delivery_date',
            'order_number',
            'quotation_net_value',
            'department.name',
            'po_attachments',
            'paymentTerm.name',
            'currency.name',
            'notes',
            'status',
            AllowedFilter::callback('order_name', function (Builder $query, $value) {
                $query->where('order_name', 'LIKE', "%{$value}%")
                    ->orWhere('order_number', 'LIKE', "%{$value}%");
            }),
        ];
    }
}
