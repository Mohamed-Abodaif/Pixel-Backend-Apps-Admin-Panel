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
use App\Models\WorkSector\SystemConfigurationModels\ExpenseType;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\ExpenseTypesResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ExpenseTypesOperations\ExpenseTypeStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ExpenseTypesOperations\ExpenseTypeDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\ExpenseTypesOperations\ExpenseTypeUpdatingService;

class ExpenseTypesController extends Controller
{
    protected $filterable = [
        'name',
        'status',
        'category'
    ];

    // public function __construct()
    // {
    //     $this->middleware('permission:read_sc-dropdown-lists')->only(['index']);
    //     $this->middleware('permission:create_sc-dropdown-lists')->only(['store']);
    //     $this->middleware('permission:read_sc-dropdown-lists')->only(['show']);
    //     $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
    //     $this->middleware('permission:delete_sc-dropdown-lists')->only(['destroy']);
    //     $this->middleware('permission:import_sc-dropdown-lists')->only(['importExpenseTypes']);
    //     $this->middleware('permission:export_sc-dropdown-lists')->only(['exportExpenseTypes']);
    // }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(ExpenseType::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);
        return Response::success(['list' => $data]);
    }

    function list(Request $request)
    {
        $data = QueryBuilder::for(ExpenseType::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return ExpenseTypesResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        return (new ExpenseTypeStoringService())->create($request);
    }

    /**
     * @param ExpenseType $type
     * @return SingleResource
     */
    public function show(ExpenseType $type)
    {
        return new SingleResource($type);
    }

    /**
     * @param Request $request
     * @param ExpenseType $type
     * @return JsonResponse
     */
    public function update(Request $request, ExpenseType $type): JsonResponse
    {
        return (new ExpenseTypeUpdatingService($type))->update($request);
    }

    /**
     * @param ExpenseType $type
     * @return JsonResponse
     */
    public function destroy(ExpenseType $type): JsonResponse
    {
        return (new ExpenseTypeDeletingService($type))->delete();
    }

    public function importExpenseTypes(Request $request)
    {
        return (new ImportBuilder())
            ->file($request->file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return ExpenseType::create($item);
            })
            ->import();
    }

    public function exportExpenseTypes(Request $request)
    {
        $expense_types =  QueryBuilder::for(ExpenseType::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Expense Types" => ExportBuilder::generator($expense_types)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label'], 'Category' => $item['category']['label']])
            ->name('Expense Types')->build();
    }
}
