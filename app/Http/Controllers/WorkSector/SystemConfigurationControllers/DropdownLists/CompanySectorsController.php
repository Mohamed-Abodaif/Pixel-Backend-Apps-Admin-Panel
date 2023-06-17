<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use App\Export\ExportBuilder;
use App\Import\ImportBuilder;
use App\Http\Requests\ImportFile;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\CompanySector;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\CompanySectorsResource;
use App\Http\Requests\WorkSector\SystemConfigurations\CompanySectorRequests\CompanySectorsRequest;

class CompanySectorsController extends Controller
{
    protected $filterable = [
        'name',
        'status'
    ];

    // public function __construct()
    // {
    //     $this->middleware('permission:read_sc-dropdown-lists')->only(['index']);
    //     $this->middleware('permission:create_sc-dropdown-lists')->only(['store']);
    //     $this->middleware('permission:read_sc-dropdown-lists')->only(['show']);
    //     $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
    //     $this->middleware('permission:delete_sc-dropdown-lists')->only(['destroy']);
    //     $this->middleware('permission:import_sc-dropdown-lists')->only(['importCompanySectors']);
    //     $this->middleware('permission:export_sc-dropdown-lists')->only(['exportCompanySectors']);
    // }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(CompanySector::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        return response()->success(['list' => $data]);
    }

    public function list(Request $request)
    {
        $data = QueryBuilder::for(CompanySector::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return CompanySectorsResource::collection($data);
    }

    public function store(CompanySectorsRequest $request)
    {
        $data = $request->items;
        CompanySector::insert($data);
        $response = [
            "message" => "Created Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function update(CompanySectorsRequest $request, $id)
    {
        $data = $request->all();

        $company_sector = CompanySector::findOrFail($id);
        $company_sector->update($data);

        $response = [
            "message" => "Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $item = CompanySector::findOrFail($id);
        return new SingleResource($item);
    }

    public function destroy($id)
    {
        $company_sector = CompanySector::findOrFail($id);
        $company_sector->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function importCompanySectors(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return CompanySector::create($item);
            })
            ->import();
    }

    public function exportCompanySectors(Request $request)
    {
        $company_sectors = QueryBuilder::for(CompanySector::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Company Sectors" => ExportBuilder::generator($company_sectors)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']])
            ->name('Company Sectors')->build();
    }
}
