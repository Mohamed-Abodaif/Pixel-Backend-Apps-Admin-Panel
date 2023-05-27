<?php

namespace App\Http\Controllers\WorkSector\HRModule;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Response;
use App\Models\WorkSector\HRModule\EmployeeTimeSheet;
use App\Http\Resources\WorkSector\HRModule\ExpenseResource;
use App\Http\Requests\WorkSector\HRModule\EmployeeTimesheetRequest;
use App\Http\Resources\WorkSector\HRModule\EmployeeTimeSheetResource;
use App\Http\Resources\WorkSector\HRModule\EmployeesTimesheetResource;

class EmployeesTimesheetController extends Controller
{

    public function list()
    {
        $data =  QueryBuilder::for(EmployeeTimeSheet::class)
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'payment_date']);
        return EmployeeTimeSheetResource::collection($data);
    }


    public function index(Request $request)
    {
        $date = $request?->filter['date'] ?? Carbon::now()->subDays(15)->toDateString();

        $data = QueryBuilder::for(EmployeeTimeSheet::class)
            ->allowedIncludes([
                'employee',
                'client',
                'clientOrder',
                'vendor',
                'vendorOrder',
                'country',
                'subCategoryTimesheet.category',
            ])
            ->allowedFilters($this->filters())
            ->customOrdering('id', 'desc')->limit(30)
            ->get();

        $timesheets = EmployeesTimesheetResource::collection($data);

        $statistics = $this->statistics(EmployeeTimeSheet::class, $request, 'employee_timesheet');
        return Response::success(['list' => $timesheets, 'statistics' => $statistics]);
    }

    public function store(EmployeeTimesheetRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        EmployeeTimeSheet::create($data);

        $response = [
            "message" => "Created Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function update(EmployeeTimesheetRequest $request, $id)
    {
        $data = $request->all();
        $timesheet = EmployeeTimeSheet::findOrFail($id);
        $timesheet->update($data);

        $response = [
            "message" => "Updated Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }
    public function destroy(int $id)
    {
        $timesheet = EmployeeTimeSheet::findOrFail($id);
        $timesheet->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }


    public function show(int $id)
    {
        $item = EmployeeTimeSheet::with(['expenseType', 'purchaseInvoice', 'currency', 'paymentMethod', 'clientOrder', 'asset', 'client'])->findOrFail($id);
        return new ExpenseResource($item);
    }

    /**
     * @return array
     */

    private function filters(): array
    {
        return [
            "created_at",
            "payment_date",
            "attachments",
            "purchaseInvoice.name",
            "notes",
            "amount",
            "paid_to",
            "duration",
            "expenseType.name",
            "currency.name",
            "paymentMethod.name",
            "category",
            "asset.asset_name",
            "client.name",
            "clientOrder.order_name",
            AllowedFilter::exact("status")
        ];
    }
}
