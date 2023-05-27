<?php

namespace App\Http\Controllers\WorkSector\HRModule;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\CreateEditRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Models\WorkSector\UsersModule\User;
use App\Models\WorkSector\HRModule\EmployeeTimeSheet;
use App\Models\WorkSector\HRModule\TimesheetDiscussion;
use App\Models\WorkSector\SystemConfigurationModels\Expense;
use App\Http\Resources\WorkSector\UsersModule\EmployeeResource;
use App\Http\Resources\WorkSector\HRModule\EmployeeTimeSheetResource;

class EmployeesTimeSheetsController extends Controller
{


    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function listEmployees(Request $request): JsonResponse
    {
        $status = $request->filter['status'] ?? 'Pending';
        $pageSize = $request->pageSize ?? 10;

        //TODO: must show only active employees //->activeEmployees()
        $users = QueryBuilder::for(User::class)->select([
            'id',
            'name',
            'mobile',
            'avatar',
            'email',
            'employee_id',
            'role_id',
            'status as employee_status',
            'department_id'
        ])
            ->whereIn(
                'id',
                EmployeeTimeSheet::select(['user_id'])
                    ->where('status', $status)
                    ->datesFiltering()
            )
            ->withCount(['timesheets' => function ($query) use ($status) {
                $query->where('status', $status)->datesFiltering();
            }])
            ->with(['role', 'department'])
            ->allowedFilters($this->employeesFilters())
            ->customOrdering()
            ->paginate($pageSize);

        //active currencies
        //  $statistics = $this->statistics(Expense::class, $request, 'timesheets_currencies', $this->getStatisticsFilter($status));

        $status_count = QueryBuilder::for(EmployeeTimeSheet::class)
            ->select('status', DB::raw('count(*) as total'))
            //->where('status', $status)
            ->datesFiltering()
            ->groupBy('status')
            ->get();

        return response()->success([
            'list' => $users,
            'status_count' => $status_count,
            // 'statistics' => $statistics
        ]);
    }

    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = QueryBuilder::for(EmployeeTimeSheet::class)->with(
            ['employee', 'client', 'clientOrder', 'vendor', 'vendorOrder', 'country', 'subCategory.category']
        )->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);

        return response()->success([
            'list' => $data,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function employeeEvents(Request $request, int $id): AnonymousResourceCollection
    {
        $status = isset($request->filter['status']) ? $request->filter['status'] : 'Pending';
        $data = QueryBuilder::for(EmployeeTimeSheet::class)
            ->withCount('disscussions')
            ->with(['employee', 'client', 'clientOrder', 'vendor', 'vendorOrder', 'country', 'subCategory.category'])
            ->allowedFilters($this->filters())
            ->where('status', $status)
            ->where('user_id', $id)
            ->datesFiltering()
            ->customOrdering('id', 'desc')
            ->paginate(1);
        return EmployeeResource::collection($data);
    }

    /**
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function acceptEvent(int $id)
    {
        $timesheet = EmployeeTimeSheet::findOrFail($id);
        $user_id = auth()->user()->id;
        $time = Carbon::now()->toDateTimeString();
        $timesheet->update([
            'accepted_by' => $user_id,
            'accepted_at' => $time,
            'status' => 'Approved'
        ]);
        $response = [
            "message" => "Accepted Successfully",
            "status" => "success",
        ];
        return response()->json($response, 200);
    }


    /**
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function rejectEvent(int $id): JsonResponse
    {
        $timesheet = EmployeeTimeSheet::findOrFail($id);
        $user_id = auth()->user()->id;
        $time = Carbon::now()->toDateTimeString();
        $timesheet->update(['rejected_by' => $user_id, 'rejected_at' => $time, 'status' => 'Rejected']);
        $response = [
            "message" => "Rejected Successfully",
            "status" => "success",
        ];
        return response()->json($response, 200);
    }

    /**
     * @param CreateEditRequest $request
     * 
     * @return JsonResponse
     */
    public function editRequest(CreateEditRequest $request)
    {
        try {
            $id = $request->get('id');
            $timesheet = EmployeeTimeSheet::findOrFail($id);

            $sender_id = auth()->user()->id;
            $receiver_id = $timesheet->user_id;
            $message = $request->get('message');
            $attachment = $request->attachment;

            DB::beginTransaction();
            //update timesheet status
            $timesheet->update(['status' => 'Edit Requested']);
            //create request edit
            TimesheetDiscussion::create([
                'timesheet_id' => $id,
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'message' => $message,
                'attachment' => $attachment
            ]);
            //commit transaction
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()
                ->json([
                    "message" => "Error occurred",
                    "status" => $th->getMessage(),
                ], 500);
        }

        return response()->json([
            "message" => "Saved Successfully",
            "status" => "success",
        ], 200);
    }

    /**
     * @return JsonResponse
     */
    public function countByStatus(): JsonResponse
    {
        $data = EmployeeTimeSheet::where('status', '!=', 'Draft')->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        return response()->json($data, 200);
    }

    /**
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $expens_type = EmployeeTimeSheet::findOrFail($id);
        $expens_type->delete();

        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    /**
     * @param int $id
     * 
     * @return ExpenseResource
     */
    /* public function show(int $id) : EmployeeTimeSheetResource
    {
        $item = EmployeeTimeSheet::with(['employee','client','clientOrder','vendor','vendorOrder','country','subCategory.category'])->findOrFail($id);
        return new EmployeeTimeSheetResource($item);
    } */



    /**
     * @return array
     */
    private function employeesFilters(): array
    {
        return [
            AllowedFilter::callback('name', function (Builder $query, ?string $value) {
                $query->where('name', 'LIKE', "%{$value}%")
                    ->orWhere('mobile', 'LIKE', "%{$value}%")
                    ->orWhere('email', 'LIKE', "%{$value}%");
            }),
            AllowedFilter::callback('employees_ids', function (Builder $query, $value) {
                // $ids=explode(',',$value);
                $query->WhereIn('id', $value);
            }),
            AllowedFilter::exact('role_id'),
            AllowedFilter::exact('department_id')
        ];
    }

    private function filters(): array
    {
        return [
            /* "created_at",
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
            AllowedFilter::exact("status") */];
    }
}
