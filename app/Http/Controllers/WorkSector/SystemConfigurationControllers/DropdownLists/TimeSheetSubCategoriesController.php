<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\SystemConfigurationModels\TimeSheetSubCategory;
use App\Services\WorkSector\SystemConfigurationServices\DropdownList\TimesheetSubCategoriesOperations\TimesheetSubCategoryStoringService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownList\TimesheetSubCategoriesOperations\TimesheetSubCategoryDeletingService;
use App\Services\WorkSector\SystemConfigurationServices\DropdownList\TimesheetSubCategoriesOperations\TimesheetSubCategoryUpdatingService;

class TimeSheetSubCategoriesController extends Controller
{
    protected $filterable = [
        'name',
        'status',
        'timesheet_category_id',
        'timesheetCategory.name'
    ];

    public function index(Request $request)
    {
        $data = QueryBuilder::for(TimeSheetSubCategory::class)
            ->allowedIncludes(['category'])
            ->allowedFilters($this->filterable)
            ->active()
            ->datesFiltering()
            ->customOrdering()
            ->paginate($request->pageSize ?? 10);
        return Response::success(['list' => $data]);
    }

    public function show(TimeSheetSubCategory $category)
    {
        return new SingleResource($category);
    }

    public function store(Request $request): JsonResponse
    {
        return (new TimesheetSubCategoryStoringService())->create($request);
    }

    public function update(Request $request, TimeSheetSubCategory $category): JsonResponse
    {
        return (new TimesheetSubCategoryUpdatingService($category))->update($request);
    }

    public function destroy(TimeSheetSubCategory $category): JsonResponse
    {
        return (new TimesheetSubCategoryDeletingService($category))->delete();
    }

    public function importTimeSheetSubCategories(Request $request)
    {
        return (new ImportBuilder())
            ->file($request->file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return TimeSheetSubCategory::create($item);
            })
            ->import();
    }

    public function exportTimeSheetSubCategories(Request $request)
    {
        $taxes = QueryBuilder::for(TimeSheetSubCategory::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
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
        TimeSheetSubCategory::where('id', $id)->update(['status' => $status]);
        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];
    }
}
