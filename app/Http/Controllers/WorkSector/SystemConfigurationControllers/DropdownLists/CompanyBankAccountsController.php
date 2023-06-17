<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\CompanyBankAccount;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\CompanyBankAccountResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyBanckAccountsOperations\CompanyBankAccountDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyBanckAccountsOperations\CompanyBankAccountStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CompanyBanckAccountsOperations\CompanyBankAccountUpdatingService;

class CompanyBankAccountsController extends Controller
{
    protected $filterable = [
        'account_name',
        'status'
    ];

    public function __construct()
    {
        // $this->middleware('permission:read_sc-dropdown-lists')->only(['index']);
        // $this->middleware('permission:create_sc-dropdown-lists')->only(['store']);
        // $this->middleware('permission:read_sc-dropdown-lists')->only(['show']);
        // $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
        // $this->middleware('permission:delete_sc-dropdown-lists')->only(['destroy']);
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importCompanyBankAccounts']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportCompanyBankAccounts']);

    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(CompanyBankAccount::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    public function show(CompanyBankAccount $companyBankAccount)
    {
        return new SingleResource($companyBankAccount);
    }

    function list()
    {
        $data = QueryBuilder::for(CompanyBankAccount::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'account_name']);
        return CompanyBankAccountResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        return (new CompanyBankAccountStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param CompanyBankAccount $companyBankAccount
     * @return JsonResponse
     */
    public function update(Request $request, CompanyBankAccount $companyBankAccount): JsonResponse
    {
        return (new CompanyBankAccountUpdatingService($companyBankAccount))->update($request);
    }

    /**
     * @param CompanyBankAccount $companyBankAccount
     * @return JsonResponse
     */
    public function destroy(CompanyBankAccount $companyBankAccount): JsonResponse
    {
        return (new CompanyBankAccountDeletingService($companyBankAccount))->delete();
    }

    public function importCompanyBankAccounts(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return CompanyBankAccount::create($item);
            })
            ->import();
    }

    public function exportCompanyBankAccounts(Request $request)
    {
        $taxes = QueryBuilder::for(CompanyBankAccount::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "CompanyBankAccounts" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('CompanyBankAccounts')->build();
    }
}
