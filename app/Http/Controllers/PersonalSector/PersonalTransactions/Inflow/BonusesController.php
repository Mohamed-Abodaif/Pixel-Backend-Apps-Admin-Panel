<?php

namespace App\Http\Controllers\PersonalSector\PersonalTransactions\Inflow;

use ExportBuilder;
use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\PersonalSector\PersonalTransactions\Inflow\Bonus;
use App\Http\Requests\PersonalSector\PersonalTransactions\Inflow\BonusRequest;
use App\Http\Resources\PersonalSector\PersonalTransactions\Inflow\BonusesResource;
use App\Services\PersonalSector\PersonalTransactions\Inflow\BonusesServices\BonusDeletingService;
use App\Services\PersonalSector\PersonalTransactions\Inflow\BonusesServices\BonusStoringService;
use App\Services\PersonalSector\PersonalTransactions\Inflow\BonusesServices\BonusUpdatingService;

class BonusesController extends Controller
{
    protected $filterable = [
        'name',
        'currency.name',
        'bonusSender.name',
        'created_at',
        'amount',
        'status'
    ];

    public function list(Request $request)
    {
        $data = QueryBuilder::for(Bonus::class)
            ->allowedFilters(['name', 'amount'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name', 'amount']);
        return BonusesResource::collection($data);
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Bonus::class)->createdBy()->with(['currency', 'bonusSender'])->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Bonus::class, $request, 'bonuses');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }


    public function show($id)
    {
        $item = Bonus::with([
            'currency',
            'bonusSender'
        ])->findOrFail($id);
        return new SingleResource($item);
    }

    public function store(Request $request)
    {
        $request['user_id'] = auth()->user()->id;

        return (new BonusStoringService())->create($request);
    }

    public function update(Request $request, Bonus $bonus): JsonResponse
    {
        return (new BonusUpdatingService($bonus))->update($request);
    }

    public function  destroy(Bonus $bonus): JsonResponse
    {
        return (new BonusDeletingService($bonus))->delete();
    }

    public function importBonuses(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Bonus::create($item);
            })
            ->import();
    }

    public function exportBonuses(Request $request)
    {
        $asset_types = QueryBuilder::for(Bonus::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Bonuses" => ExportBuilder::generator($asset_types)
        ]);

        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Bonuses')->build();
    }

    public function duplicate(BonusRequest $request, $id)
    {
        $data = $request->all();
        $recored = Bonus::find($id);
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
        Bonus::where('id', $id)->update(['status' => $status]);
        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }
}
