<?php

namespace App\Http\Controllers\WorkSector\FinancesModule\TaxesAndInsurances;

use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\WorkSector\FinancesModule\TaxesAndInsurances\InsurancTypesRequest;
use App\Http\Resources\WorkSector\FinancesModule\TaxesAndInsurances\InsurancTypesResource;

class InsurancTypeController extends Controller
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
    // }
    //
    public function index(Request $request)
    {
        $data = QueryBuilder::for(InsurancType::class)->allowedFilters($this->filterable)->customOrdering()->paginate(request()->pageSize ?? 10);
        return response()->success(['list' => $data]);
    }
    /* public function index(Request $request)
    {
        $pageSize = $request->pageSize ?? 10;
        $sort = $request->sort;
        $sortColumn = $request->sortColumn;
        $keyword = $request->keyword;
        $data = InsurancType::query();
        if (isset($sort) && $sortColumn) {
            $data->orderBy($sortColumn, $sort);
        }
        if (isset($keyword)) {
            $data->where('name', 'like', '%' . $keyword . '%');
        }
        $insurance_types = $data->get();
        $insurance_types = InsurancTypesResource::collection($insurance_types);
        return $this->paginateCollection($insurance_types, $pageSize,null, ['path' => env('APP_URL') . '/' . request()->path()]);

    } */

    function list(Request $request)
    {
        $data = QueryBuilder::for(InsurancType::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return InsurancTypesResource::collection($data);
    }


    public function store(InsurancTypesRequest $request)
    {
        $data = $request->items;

        InsurancType::create($data);

        $response = [
            "message" => "Created Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function update(InsurancTypesRequest $request, $id)
    {
        $data = $request->all();

        $Insuranc_type = InsurancType::findOrFail($id);
        $Insuranc_type->update($data);

        $response = [
            "message" => "Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $Insuranc_type = InsurancType::findOrFail($id);
        $Insuranc_type->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }
    public function importInsurancTypes(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return InsurancType::create($item);
            })
            ->import();
    }
}
