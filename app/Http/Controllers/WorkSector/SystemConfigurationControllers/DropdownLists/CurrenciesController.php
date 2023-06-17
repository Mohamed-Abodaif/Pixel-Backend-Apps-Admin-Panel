<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\Currency;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\CurrenciesResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\CurrenciesOperations\CurrencyUpdatingService;

class CurrenciesController extends Controller
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
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importCurrencys']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportCurrencies']);
    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(Currency::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()->customOrdering()
            ->paginate($request->pageSize ?? 10);
        return Response::success(['list' => $data]);
    }

    function list(Request $request)
    {
        $pageSize = $request->pageSize ?? 10;
        $data = QueryBuilder::for(Currency::class)
            ->allowedFilters(['name', 'symbol'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name', 'symbol']);
        return CurrenciesResource::collection($data);
    }
    public function update(Request $request, Currency $currency): JsonResponse
    {
        return (new CurrencyUpdatingService($currency))->update($request);
    }

    public function importCurrencies(Request $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Currency::create($item);
            })
            ->import();
    }

    public function exportCurrencies(Request $request)
    {
        $taxes = QueryBuilder::for(Currency::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Currenciess" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Currencies')->build();
    }

    public function setMainCurrency(Request $request, Currency $currency): JsonResponse
    {
        $request->merge(['is_main' => 1]);
        return (new CurrencyUpdatingService($currency))->update($request);
    }
}
