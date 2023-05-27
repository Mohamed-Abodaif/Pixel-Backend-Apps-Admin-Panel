<?php

namespace App\Http\Controllers\WorkSector\VendorsModule;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\VendorsModule\Vendor;
use App\Http\Requests\WorkSector\VendorsModule\VendorRequest;
use App\Http\Resources\WorkSector\VendorsModule\VendorResource;
use App\Services\WorkSector\VendorsModule\VendorServices\VendorDeletingService;
use App\Services\WorkSector\VendorsModule\VendorServices\VendorStoringService;
use App\Services\WorkSector\VendorsModule\VendorServices\VendorUpdatingService;

class VendorsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:read_cmm-vendors-list')->only(['index']);
    //     $this->middleware('permission:create_cmm-vendors-list')->only(['store']);
    //     $this->middleware('permission:read_cmm-vendors-list')->only(['show']);
    //     $this->middleware('permission:edit_cmm-vendors-list')->only(['update']);
    //     $this->middleware('permission:delete_cmm-vendors-list')->only(['destroy']);
    //     $this->middleware('permission:import_cmm-vendors-list')->only(['importVendors']);
    //     $this->middleware('permission:export_cmm-vendors-list')->only(['exportVendors']);
    // }

    //
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Vendor::class)->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Vendor::class, $request, 'vendors');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    public function show($id)
    {
        $item = Vendor::findOrFail($id);
        return new SingleResource($item);
    }

    function list(Request $request)
    {
        $vendors = QueryBuilder::for(Vendor::class)
            ->allowedFilters(['name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return VendorResource::collection($vendors);

        // return QueryBuilder::for(Vendor::class)->allowedFilters(['name'])->get();
    }

    public function store(Request $request)
    {
        return (new VendorStoringService())->create($request);
    }

    public function update(Request $request, Vendor $vendor): JsonResponse
    {
        return (new VendorUpdatingService($vendor))->update($request);
    }

    public function  destroy(Vendor $vendor): JsonResponse
    {
        return (new VendorDeletingService($vendor))->delete();
    }

    public function getVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        return $vendor;
    }
    public function importVendors(ImportFile $import)
    {
        $file = $import->file;
        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Vendor::create($item);
            })
            ->import();
    }

    public function exportVendors(Request $request)
    {
        $taxes = QueryBuilder::for(Vendor::class)->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Vendors" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Vendors')->build();
    }

    public function duplicate(VendorRequest $request, $id)
    {
        $data = $request->all();
        $recored = Vendor::find($id);
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
        Vendor::where('id', $id)->update(['status' => $status]);

        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }


    public function filters(): array
    {
        return [
            'billing_address',
            'registration_no',
            'tax_no',
            'type',
            'name',
            'country',
            'status',
            'created_at',
            'country'
        ];
    }
}
