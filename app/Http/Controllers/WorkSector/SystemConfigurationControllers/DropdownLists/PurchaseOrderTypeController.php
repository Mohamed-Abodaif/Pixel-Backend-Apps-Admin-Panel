<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\ClientsModule\PurchaseOrderType;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\PurchaseOrderTypeResource;
use App\Http\Requests\WorkSector\SystemConfigurations\PurchaseOrderType\PurchaseOrderTypeRequest;

class PurchaseOrderTypeController extends Controller
{
    protected $filterable = [
        "name",
        "status"
    ];
    // public function __construct()
    // {
    //     $this->middleware('permission:read_sc-dropdown-lists')->only(['index']);
    //     $this->middleware('permission:create_sc-dropdown-lists')->only(['store']);
    //     $this->middleware('permission:read_sc-dropdown-lists')->only(['show']);
    //     $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
    //     $this->middleware('permission:delete_sc-dropdown-lists')->only(['destroy']);
    //     $this->middleware('permission:import_sc-dropdown-lists')->only(['importPurchaseOrderTypes']);
    //     $this->middleware('permission:export_sc-dropdown-lists')->only(['exportPurchaseOrderTypes']);
    // }
    //
    public function index(Request $request)
    {
        $data = QueryBuilder::for(PurchaseOrderType::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        return response()->success(['list' => $data]);
    }

    function list(Request $request)
    {
        $data = QueryBuilder::for(PurchaseOrderType::class)
            ->allowedFilters(['purchase_invoice_name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return PurchaseOrderTypeResource::collection($data);
    }

    public function show($id)
    {
        $item = PurchaseOrderType::with('vendor', 'department', 'currency')->findOrFail($id);
        return new SingleResource($item);
    }

    public function store(PurchaseOrderTypeRequest $request)
    {
        $data = $request->all();
        PurchaseOrderType::create($data);
        $response = [
            "message" => "Created Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }
    public function update(PurchaseOrderTypeRequest $request, $id)
    {
        $data = $request->all();
        $PurchaseOrderType = PurchaseOrderType::findOrFail($id);
        $PurchaseOrderType->update($data);
        $response = [
            "message" => "Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $PurchaseOrderType = PurchaseOrderType::findOrFail($id);
        $PurchaseOrderType->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function importPurchaseOrderTypes(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return PurchaseOrderType::create($item);
            })
            ->import();
    }

    public function exportPurchaseOrderTypes(Request $request)
    {
        $purchase_order_types = QueryBuilder::for(PurchaseOrderType::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Purchase Order Types" => ExportBuilder::generator($purchase_order_types)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ["No." => $item["id"], "Name" => $item["name"], "Status" => $item["status"]["label"]])
            ->name("Purchase Order Types")->build();
    }
}
