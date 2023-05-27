<?php

namespace App\Http\Controllers\WorkSector\InventoryModule;

use Illuminate\Http\Request;
use App\Export\ExportBuilder;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\InventoryModule\Service;
use App\Models\WorkSector\InventoryModule\ServiceSalesPrice;
use App\Models\WorkSector\InventoryModule\ServiceVendorPrice;
use App\Http\Requests\WorkSector\InventoryModule\ServiceRequest;
use App\Http\Resources\WorkSector\InventoryModule\ServicesResource;

class ServicesController extends Controller
{

    protected $filterable = [
        'service_name',
        'created_at',
    ];

    public function __construct()
    {
    }

    public function list(Request $request)
    {
        $data = QueryBuilder::for(Service::class)
            ->allowedFilters(['service_name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'service_name as name']);
        return ServicesResource::collection($data);
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Service::class)->with(['serviceCategory', 'department'])->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Service::class, $request, 'services');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->only(
            'service_name',
            'department_id',
            'category_id',
            'description',
            'tools_certificates',
            'manual_attachment'
        );
        // dd($data,$request->all());

        $service = Service::create($data);
        $service_sale_prices = $request->service_sale_prices;
        $service_vendors_prices = $request->service_vendors_prices;


        if (isset($service_sale_prices)) {
            foreach ($service_sale_prices as $service_sale_price) {
                $service_sale_price['service_id'] = $service->id;

                ServiceSalesPrice::create($service_sale_price);
            }
        }
        if (isset($service_vendors_prices)) {
            foreach ($service_sale_prices as $service_sale_price) {
                $service_sale_price['service_id'] = $service->id;

                ServiceVendorPrice::create($service_sale_price);
            }
        }
        $response = [
            "message" => "Created Successfully",
            "status" => "success",
            "data" => $service
        ];
        return response()->json($response, 200);
    }

    public function update(ServiceRequest $request, $id)
    {
        $data = $request->all();

        $expens_type = Service::findOrFail($id);
        $expens_type->update($data);
        $response = [
            "message" => "Updated Successfully",
            "status" => "success",
            "data" => $expens_type
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $expens_type = Service::findOrFail($id);
        $expens_type->delete();
        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $item = Service::with(['department', 'serviceCategory'])->findOrFail($id);
        return new SingleResource($item);
    }
    public function importServices(ImportFile $import)
    {
        $file = $import->file;
        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Service::create($item);
            })
            ->import();
    }

    public function exportServices(Request $request)
    {
        $services = QueryBuilder::for(Service::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Services" => ExportBuilder::generator($services)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['service_name'], 'Status' => $item['status']])
            ->name('Services')->build();
    }

    public function duplicate(ServiceRequest $request, $id)
    {
        $data = $request->all();
        $recored = Service::find($id);
        $copy = $recored->replicate()->fill($data);
        $copy->save();
        $response = [
            "message" => "duplicated Successfully",
            "status" => "success",
            "data" => $copy
        ];
        return response()->json($response, 200);
    }

    public function changeStatus(Request $request, $id)
    {
        $status = $request->status;
        Service::where('id', $id)->update(['status' => $status]);
        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }
}
