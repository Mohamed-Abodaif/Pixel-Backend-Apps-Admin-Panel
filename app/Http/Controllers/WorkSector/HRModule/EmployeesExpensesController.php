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
use App\Http\Resources\WorkSector\HRModule\ExpenseResource;
use App\Models\WorkSector\SystemConfigurationModels\Expense;
use App\Http\Resources\WorkSector\UsersModule\EmployeeResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\WorkSector\SystemConfigurationModels\ExpenseDiscussion;

class EmployeesExpensesController extends Controller
{

    /**
     * @param Request $request
     * 
     * @return AnonymousResourceCollection
     */
    public function list(): AnonymousResourceCollection
    {
        $data =  QueryBuilder::for(Expense::class)
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'payment_date']);
        return ExpenseResource::collection($data);
    }
    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = QueryBuilder::for(Expense::class)->with(['expenseType', 'purchaseInvoice', 'currency', 'paymentMethod', 'clientOrder', 'asset', 'client'])->allowedFilters($this->filters())->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Expense::class, $request, 'expenses');

        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function employeeExpenses(Request $request, int $id): AnonymousResourceCollection
    {
        $status = isset($request->filter['status']) ? $request->filter['status'] : 'Pending';
        $data = QueryBuilder::for(Expense::class)
            ->withCount('disscussions')
            ->with(['expenseType', 'purchaseInvoice', 'currency', 'paymentMethod', 'clientOrder', 'asset', 'client'])
            ->allowedFilters($this->filters())
            ->where('status', $status)
            ->where('user_id', $id)
            ->datesFiltering()
            //->customOrdering()
            ->paginate(1);
        return EmployeeResource::collection($data);
    }


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
                Expense::select(['user_id'])
                    ->where('status', $status)
                    ->datesFiltering()
            )
            ->withCount(['expenses' => function ($query) use ($status) {
                $query->where('status', $status)->datesFiltering();
            }])
            ->with(['role', 'department'])
            ->allowedFilters($this->employeesFilters())
            ->customOrdering()
            ->paginate($pageSize);

        //active currencies
        $statistics = $this->statistics(Expense::class, $request, 'expenses_currencies', $this->getStatisticsFilter($status));

        $status_count = QueryBuilder::for(Expense::class)
            ->select('status', DB::raw('count(*) as total'))
            //->where('status', $status)
            ->datesFiltering()
            ->groupBy('status')
            ->get();

        return response()->success([
            'list' => $users,
            'status_count' => $status_count,
            'statistics' => $statistics
        ]);
    }

    /**
     * @param int $id
     * 
     * @return JsonResponse
     */
    public function acceptExpense(int $id)
    {
        $expense = Expense::findOrFail($id);
        $user_id = auth()->user()->id;
        $time = Carbon::now()->toDateTimeString();
        $expense->update([
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
    public function rejectExpense(int $id): JsonResponse
    {
        $expense = Expense::findOrFail($id);
        $user_id = auth()->user()->id;
        $time = Carbon::now()->toDateTimeString();
        $expense->update(['rejected_by' => $user_id, 'rejected_at' => $time, 'status' => 'Rejected']);
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
            $expense = Expense::findOrFail($id);

            $sender_id = auth()->user()->id;
            $receiver_id = $expense->user_id;
            $message = $request->get('message');
            $attachment = $request->attachment;

            DB::beginTransaction();
            //update expense status
            $expense->update(['status' => 'Edit Requested']);
            //create request edit
            ExpenseDiscussion::create([
                'expense_id' => $id,
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
        $data = Expense::where('status', '!=', 'Draft')->select('status', DB::raw('count(*) as total'))
            ->groupBy('browser')
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
        $expens_type = Expense::findOrFail($id);
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
    public function show(int $id): ExpenseResource
    {
        $item = Expense::with(['expenseType', 'purchaseInvoice', 'currency', 'paymentMethod', 'clientOrder', 'asset', 'client'])->findOrFail($id);
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

    /**
     * @param mixed $status
     * 
     * @return array
     */
    private function getStatisticsFilter(?string $status): array
    {
        return [
            'where' => [
                ['expenses.status', $status],
            ],
            'whereNull' => [
                ['currencies.deleted_at'],
                ['expenses.deleted_at']
            ],
        ];
    }
}
