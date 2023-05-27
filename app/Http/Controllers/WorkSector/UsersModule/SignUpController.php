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
use App\Http\Resources\WorkSector\UsersModule\UserResource;
use App\Services\WorkSector\UsersModule\AccountStatusChanger\SignUpAccountStatusChanger;
use App\Services\WorkSector\UsersModule\UpdatingUserByAdminService\UpdatingUserByAdminService;

class SignUpController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('permission:read_emm-signup-list')->only(['index']);
    //     $this->middleware('permission:create_emm-signup-list')->only(['store']);
    //     $this->middleware('permission:read_emm-signup-list')->only(['show']);
    //     $this->middleware('permission:edit_emm-signup-list')->only(['update']);
    //     $this->middleware('permission:delete_emm-signup-list')->only(['destroy']);
    //     // $this->middleware('permission:import_emm-signup-list')->only(['importCustodies']);
    //     $this->middleware('permission:export_emm-signup-list')->only(['exportSignUpUsers']);
    // }

    public function index(Request $request)
    {
        $data = QueryBuilder::for(User::class)
            ->with(['profile', 'profile.country'])
            ->allowedFilters($this->filters())
            ->activeSignup()
            ->datesFiltering()
            ->customOrdering()
            ->paginate($request->pageSize ?? 10);

        $statistics = $this->statistics(User::class, $request, 'signup_requests');

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
        return (new SignUpAccountStatusChanger($user))->change($request);
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
            AllowedFilter::callback('name', function (Builder $query, $value) {
                $query->where('first_name', 'LIKE', "%{$value}%")
                    ->orWhere('last_name', 'LIKE', "%{$value}%")
                    ->orWhere('mobile', 'LIKE', "%{$value}%")
                    ->orWhere('email', 'LIKE', "%{$value}%");
            })
        ];
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\FileTypeNotSupported
     */
    public function exportSignUpUsers(Request $request)
    {
        $taxes = QueryBuilder::for(User::class)
            ->with(['profile', 'profile.country', 'role'])
            ->allowedFilters($this->filters())
            ->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Sign Up Users" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Sign Up Users')->build();
    }
}
