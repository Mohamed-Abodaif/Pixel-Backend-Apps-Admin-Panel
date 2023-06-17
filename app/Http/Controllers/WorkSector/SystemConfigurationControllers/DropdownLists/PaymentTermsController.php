<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\PaymentTerm;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\PaymentTermsResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\PaymentTermsOperations\PaymentTermStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\PaymentTermsOperations\PaymentTermDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\PaymentTermsOperations\PaymentTermUpdatingService;

class PaymentTermsController extends Controller
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
    //     $this->middleware('permission:import_sc-dropdown-lists')->only(['importPaymentTerms']);
    //     $this->middleware('permission:export_sc-dropdown-lists')->only(['exportPaymentTerms']);
    // }
    //
    public function index(Request $request)
    {
        $data = QueryBuilder::for(PaymentTerm::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);
        return Response::success(['list' => $data]);
    }

    function list()
    {
        $data = QueryBuilder::for(PaymentTerm::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return PaymentTermsResource::collection($data);
    }

    public function store(Request $request): JsonResponse
    {
        return (new PaymentTermStoringService())->create($request);
    }

    public function show(PaymentTerm $term)
    {
        return new SingleResource($term);
    }
    public function update(Request $request, PaymentTerm $term): JsonResponse
    {
        return (new PaymentTermUpdatingService($term))->update($request);
    }

    public function destroy(PaymentTerm $term): JsonResponse
    {
        return (new PaymentTermDeletingService($term))->delete();
    }

    public function importPaymentTerms(Request $request)
    {
        return (new ImportBuilder())
            ->file($request->file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return PaymentTerm::create($item);
            })
            ->import();
    }

    public function exportPaymentTerms(Request $request)
    {
        $payment_term = QueryBuilder::for(PaymentTerm::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Payment Terms" => ExportBuilder::generator($payment_term)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ["No." => $item["id"], "Name" => $item["name"], "Status" => $item["status"]["label"]])
            ->name("Payment Terms")->build();
    }
}
