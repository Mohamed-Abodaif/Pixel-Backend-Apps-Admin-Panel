<?php

namespace App\Http\Controllers\WorkSector\UsersModule;


use Illuminate\Http\Request;
use App\Import\ImportBuilder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use App\Models\WorkSector\UsersModule\User;
use App\Services\WorkSector\UsersModule\UserRoleChanger;
use App\Http\Resources\WorkSector\UsersModule\UserResource;
use App\Services\UserManagementServices\UserUpdatingService\PasswordChanger;
use App\Services\WorkSector\UsersModule\UserDeletingService\UserDeletingService;
use App\Services\WorkSector\UsersModule\UserProfileUpdatingService\UserProfileUpdatingService;

class UserController extends Controller
{

    public function updateProfile(Request $request): JsonResponse
    {
        $updatingProfile = new UserProfileUpdatingService(auth("api")->user());
        return $updatingProfile->change($request);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $passwordChanger = new PasswordChanger(auth("api")->user());
        return $passwordChanger->change($request);
    }

    public function getUser(User $user): JsonResponse
    {
        $data = (new UserResource($user))->toArray(app('request'));
        return Response::success($data);
    }

    public function importUsers(Request $request)
    {
        return (new ImportBuilder())->file($request->file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return User::create($item);
            })
            ->import();
    }


    public function updateUserRole(Request $request, User $user): JsonResponse
    {
        return (new UserRoleChanger($user))->change($request);
    }


    public function getCurrentUserProfile()
    {
        /*** @var User $user */
        $user = auth("api")->user();
        $userProfile = $user->profile;

        $data = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'name' => $user->name,
            'mobile' => $user->mobile,
            "avatar" => $userProfile->avatar,
            "gender" => $userProfile->gender,
            "national_id_number" => $userProfile->national_id_number,
            "national_id_files" => $userProfile->national_id_files,
            "passport_number" => $userProfile->passport_number,
            "passport_files" => $userProfile->passport_number,
            "country" => $userProfile->country->toArray()
        ];
        return new SingleResource($data);
    }


    public function destroy(User $user): JsonResponse
    {
        return (new UserDeletingService())->delete($user);
    }

    public function checkAuth(): JsonResponse
    {
        $responseData = (new UserResource(auth("api")->user()))->toArray(app('request'));
        return Response::success($responseData, ["login successfully"], 200);
    }
}
