<?php

namespace App\Http\Controllers\WorkSector\CompanyModule;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use App\Models\WorkSector\CompanyModule\Company;
use App\Http\Requests\WorkSector\CompanyModule\CompanyRequest;
use App\Http\Requests\WorkSector\CompanyModule\CheckStatusRequest;
use App\Http\Resources\WorkSector\CompanyModule\CompaniesResource;

class CompaniesController extends Controller
{
    protected $filterable = [
        'name',
        'company_sector',
        'company_size',
        'status'
    ];


    function list()
    {
        $data = QueryBuilder::for(Company::class)
            ->allowedFilters(['name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'amount']);
        return CompaniesResource::collection($data);
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Company::class)->createdBy()->with(['companySector'])->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Company::class, $request, 'companies');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    public function show($id)
    {
        $item = Company::with('companySector', 'country')->findOrFail($id);

        return new SingleResource($item);
    }



    public function hide($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function delete($id)
    {
        $company = Company::withTrashed()->find($id)->forceDelete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }
    public function duplicate(CompanyRequest $request, $id)
    {
        $data = $request->all();
        $recored = Company::find($id);
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
        Company::where('id', $id)->update(['registration_status' => $status]);

        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }
}
