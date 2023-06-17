<?php

namespace App\Http\Controllers\WorkSector\PurchaseRequestsModule;

use Illuminate\Http\Request;
use App\Export\ExportBuilder;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkSector\ClientsModule\PurchaseRequestRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Http\Resources\WorkSector\ClientsModule\PurchaseRequestResource;
use App\Models\WorkSector\ClientsModule\PurchaseRequest;
use App\Services\WorkSector\ClientsModule\PurchaseRequestServices\PurchaseRequestDeletingService;
use App\Services\WorkSector\ClientsModule\PurchaseRequestServices\PurchaseRequestStoringService;
use App\Services\WorkSector\ClientsModule\PurchaseRequestServices\PurchaseRequestUpdatingService;

class PurchaseRequestsController extends Controller
{

    function list(Request $request)
    {
        $data = QueryBuilder::for(PurchaseRequest::class)
            ->allowedFilters(['title'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'title as name']);
        return PurchaseRequestResource::collection($data);
    }
    
    public function index(Request $request)
    {
        $data = QueryBuilder::for(PurchaseRequest::class)->with(['client', 'department'])->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(PurchaseRequest::class, $request, 'purchases_requests');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }


    public function store(Request $request)
    {
        return (new PurchaseRequestStoringService())->create($request);
    }

    public function update(Request $request, PurchaseRequest $client)
    {
        return (new PurchaseRequestUpdatingService($client))->update($request);
    }

    public function destroy(PurchaseRequest $client)
    {
        return (new PurchaseRequestDeletingService($client))->delete();
    }


    public function show($id)
    {
        $item = PurchaseRequest::with([
            'client',
            'department',
        ])->findOrFail($id);
        return new SingleResource($item);
    }

    public function importPurchaseRequests(ImportFile $import)
    {
        $file = $import->file;
        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return PurchaseRequest::create($item);
            })
            ->import();
    }

    public function exportPurchaseRequests(Request $request)
    {
        $taxes = QueryBuilder::for(PurchaseRequest::class)->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Purchases Requests" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['title']])
            ->name('Purchases Requests')->build();
    }

    public function duplicate(PurchaseRequestRequest $request, $id)
    {
        $data = $request->all();
        $recored = PurchaseRequest::find($id);
        $copy = $recored->replicate()->fill($data);
        $copy->save();

        $response = [
            "message" => "duplicated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }


    private function filters(): array
    {
        return [
            'created_at',
            'client.name',
            'department.name',
            'title',
            'status',
            'date',
        ];
    }
}
