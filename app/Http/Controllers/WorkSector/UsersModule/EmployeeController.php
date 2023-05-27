<?php

namespace App\Http\Controllers\WorkSector\UsersModule;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\WorkSector\UsersModule\UserResource;
use App\Http\Resources\WorkSector\UsersModule\EmployeesResource;
use App\Services\WorkSector\UsersModule\AccountStatusChanger\EmployeeAccountStatusChanger;
use App\Services\WorkSector\UsersModule\UpdatingUserByAdminService\UpdatingUserByAdminService;

class EmployeeController extends Controller
{

    public function __construct()
    {
        // $this->middleware('permission:read_emm-employees-list')->only(['index']);
        // $this->middleware('permission:create_emm-employees-list')->only(['store']);
        // $this->middleware('permission:read_emm-employees-list')->only(['show']);
        // $this->middleware('permission:edit_emm-employees-list')->only(['update']);
        // $this->middleware('permission:delete_emm-employees-list')->only(['destroy']);
        // // $this->middleware('permission:import_emm-employees-list')->only(['importCustodies']);
        // $this->middleware('permission:export_emm-employees-list')->only(['exportEmployees']);
    }

    public function list()
    {
        $data = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::callback('name', function (Builder $query, $value) {
                    $query->where('first_name', 'LIKE', "%{$value}%")
                        ->orWhere('last_name', 'LIKE', "%{$value}%")
                        ->orWhere('mobile', 'LIKE', "%{$value}%")
                        ->orWhere('email', 'LIKE', "%{$value}%");
                })
            ])
            ->activeEmployees()
            ->customOrdering('created_at', 'desc')
            ->select("id", "name")
            ->get();
        return EmployeesResource::collection($data);
    }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(User::class)
            ->with(['profile', 'profile.country', 'role', 'department'])
            ->allowedFilters($this->filters())
            ->activeEmployees()
            ->datesFiltering()
            ->customOrdering('accepted_at', 'desc')
            ->paginate($request->pageSize ?? 10);
        $statistics = $this->statistics(User::class, $request, 'employees');
        return Response::success(['list' => $data, 'statistics' => $statistics]);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }


    public function update(Request $request, User $user): JsonResponse
    {
        $updatingUser = new UpdatingUserByAdminService($user);
        return $updatingUser->change($request);
    }


    public function changeAccountStatus(User $user, Request $request): JsonResponse
    {
        return (new EmployeeAccountStatusChanger($user))->change($request);
    }

    public function getEmployee(User $user): JsonResource
    {
        return new UserResource($user);
    }


    public function filters(): array
    {
        return  [
            "created_at",
            "accepted_at",
            "status",
            AllowedFilter::exact("gender", "profile.gender"),
            AllowedFilter::partial("national_id_number", "profile.national_id_number"),
            AllowedFilter::partial("passport_number", "profile.passport_number"),
            AllowedFilter::partial("country", "profile.country.name"),
            AllowedFilter::partial("department", 'department.name'),
            AllowedFilter::partial("role", 'role.name'),
            AllowedFilter::callback('name', function (Builder $query, $value) {
                $query->where('first_name', 'LIKE', "%{$value}%")
                    ->orWhere('last_name', 'LIKE', "%{$value}%")
                    ->orWhere('mobile', 'LIKE', "%{$value}%")
                    ->orWhere('email', 'LIKE', "%{$value}%");
            })
        ];
    }

    public function exportEmployees(Request $request)
    {
        $taxes = QueryBuilder::for(User::class)->allowedFilters($this->filters())->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Employees" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Employees')->build();
    }
}
