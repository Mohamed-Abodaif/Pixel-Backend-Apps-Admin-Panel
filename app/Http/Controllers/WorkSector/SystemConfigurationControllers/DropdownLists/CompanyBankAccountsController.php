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
use App\Models\WorkSector\SystemConfigurationModels\ComapnyBankAccount;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\ComapnyBankAccountResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownList\ComapnyBankAccountsOperations\ComapnyBankAccountStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownList\ComapnyBankAccountsOperations\ComapnyBankAccountDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownList\ComapnyBankAccountsOperations\ComapnyBankAccountUpdatingService;

class CompanyBankAccountsController extends Controller
{
    protected $filterable = [
        'name',
        'status'
    ];

    public function __construct()
    {
        // $this->middleware('permission:read_sc-dropdown-lists')->only(['index']);
        // $this->middleware('permission:create_sc-dropdown-lists')->only(['store']);
        // $this->middleware('permission:read_sc-dropdown-lists')->only(['show']);
        // $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
        // $this->middleware('permission:delete_sc-dropdown-lists')->only(['destroy']);
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importComapnyBankAccounts']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportComapnyBankAccounts']);

    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(ComapnyBankAccount::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    public function show(ComapnyBankAccount $department)
    {
        return new SingleResource($department);
    }

    function list()
    {
        $data = QueryBuilder::for(ComapnyBankAccount::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return ComapnyBankAccountResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        return (new ComapnyBankAccountStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param ComapnyBankAccount $department
     * @return JsonResponse
     */
    public function update(Request $request, ComapnyBankAccount $department): JsonResponse
    {
        return (new ComapnyBankAccountUpdatingService($department))->update($request);
    }

    /**
     * @param ComapnyBankAccount $department
     * @return JsonResponse
     */
    public function destroy(ComapnyBankAccount $department): JsonResponse
    {
        return (new ComapnyBankAccountDeletingService($department))->delete();
    }

    public function importComapnyBankAccounts(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return ComapnyBankAccount::create($item);
            })
            ->import();
    }

    public function exportComapnyBankAccounts(Request $request)
    {
        $taxes = QueryBuilder::for(ComapnyBankAccount::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "ComapnyBankAccounts" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('ComapnyBankAccounts')->build();
    }
}
