<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\TimeSheetCategory;
use App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists\TimeSheetCategoryResource;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TimesheetCategoriesOperations\TimesheetCategoryStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TimesheetCategoriesOperations\TimesheetCategoryDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownLists\TimesheetCategoriesOperations\TimesheetCategoryUpdatingService;

class TimeSheetCategoriesController extends Controller
{
    protected $filterable = [
        'id',
        'name',
        'status'
    ];

    public function index(Request $request)
    {
        $data = QueryBuilder::for(TimeSheetCategory::class)
            ->allowedFilters($this->filterable)
            ->datesFiltering()
            ->customOrdering()
            ->paginate($request->pageSize ?? 10);
        return Response::success(['list' => $data]);
    }

    public function list()
    {
        $data = QueryBuilder::for(TimeSheetCategory::class)
            ->allowedFilters(['name'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return TimeSheetCategoryResource::collection($data);
    }

    public function timesheetFilters()
    {
        $data = QueryBuilder::for(TimeSheetCategory::class)
            ->allowedIncludes('subCategory')
            ->allowedFilters(['id'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return Response::success(['list' => $data]);
    }


    public function show(TimeSheetCategory $category)
    {
        return new SingleResource($category);
    }

    public function store(Request $request): JsonResponse
    {
        return (new TimesheetCategoryStoringService())->create($request);
    }

    public function update(Request $request, TimeSheetCategory $category): JsonResponse
    {
        return (new TimesheetCategoryUpdatingService($category))->update($request);
    }

    public function destroy(TimeSheetCategory $category): JsonResponse
    {
        return (new TimesheetCategoryDeletingService($category))->delete();
    }

    public function importTimeSheetCategories(Request $request)
    {
        return (new ImportBuilder())
            ->file($request->file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return TimeSheetCategory::create($item);
            })
            ->import();
    }

    public function exportTimeSheetCategories(Request $request)
    {
        $taxes = QueryBuilder::for(TimeSheetCategory::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Tax Types" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Tax Types')->build();
    }

    public function changeStatus(Request $request, $id)
    {
        $status = $request->status;
        TimeSheetCategory::where('id', $id)->update(['status' => $status]);
        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }
}
