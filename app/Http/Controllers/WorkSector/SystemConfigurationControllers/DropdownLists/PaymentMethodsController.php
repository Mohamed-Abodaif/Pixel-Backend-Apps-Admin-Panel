<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\PaymentMethod;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\PaymentMethodsResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\PaymentMethodsOperations\PaymentMethodStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\PaymentMethodsOperations\PaymentMethodDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\PaymentMethodsOperations\PaymentMethodUpdatingService;

class PaymentMethodsController extends Controller
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
    //     $this->middleware('permission:import_sc-dropdown-lists')->only(['importPaymentMethods']);
    //     $this->middleware('permission:export_sc-dropdown-lists')->only(['exportPaymentMethods']);
    // }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(PaymentMethod::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);
        return response()->success(['list' => $data]);
    }

    public function list()
    {
        $data = QueryBuilder::for(PaymentMethod::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return PaymentMethodsResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        return (new PaymentMethodStoringService())->create($request);
    }

    /**
     * @param Request $request
     * @param PaymentMethod $method
     * @return JsonResponse
     */
    public function update(Request $request, PaymentMethod $method): JsonResponse
    {
        return (new PaymentMethodUpdatingService($method))->update($request);
    }

    public function show(PaymentMethod $method)
    {
        return new SingleResource($method);
    }

    public function destroy(PaymentMethod $method): JsonResponse
    {
        return (new PaymentMethodDeletingService($method))->delete();
    }

    public function importPaymentMethods(Request $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return PaymentMethod::create($item);
            })
            ->import();
    }

    public function exportPaymentMethods(Request $request)
    {
        $payment_methods = QueryBuilder::for(PaymentMethod::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Payment Methods" => ExportBuilder::generator($payment_methods)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Payment Methods')->build();
    }
}
