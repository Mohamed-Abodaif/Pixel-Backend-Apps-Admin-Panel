<?php

namespace App\Services\WorkSector\UsersModule\AccountStatusChanger;

use Exception;

use App\Helpers\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Services\WorkSector\UsersModule\UserRoleChanger;
use App\Services\WorkSector\UsersModule\UserUpdatingService;
use App\Http\Requests\WorkSector\UsersModule\UserStatusUpdatingRequest;
use App\Notifications\UserNotifications\StatusNotifications\ActiveRegistrationNotification;
use App\Notifications\UserNotifications\StatusNotifications\BlockedRegistrationNotification;
use App\Notifications\UserNotifications\StatusNotifications\PendingRegistrationNotification;
use App\Notifications\UserNotifications\StatusNotifications\InactiveRegistrationNotification;

abstract class AccountStatusChanger extends UserUpdatingService
{

    //Here We get the convenient Notification Class by mapping it to status value
    // that is more dynamic and with this solution there is no need to more if conditions
    const StatusNotificationMap = [
        0 => PendingRegistrationNotification::class,
        1 => ActiveRegistrationNotification::class,
        2 => InactiveRegistrationNotification::class,
        3 => BlockedRegistrationNotification::class,
    ];

    public function getRequestKeysToValidation(): array
    {
        $this->validator->AllRules();
        return ["allRules"];
    }

    protected function getRequestForm(): string
    {
        return UserStatusUpdatingRequest::class;
    }

    private function setEmployeeUserType()
    {
        $this->user->accepted_at = now();
        $this->user->user_type = "employee";
    }
    private function setDefaultUserType()
    {
        $this->user->user_type = "signup";
        $this->user->accepted_at = null;
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    private function setUserRole(array $data): bool
    {
        if (!isset($data["role_id"])) {
            throw new Exception("Role ID Has Not Been Sent");
        }
        $roleChangingService = (new UserRoleChanger($this->user))->change($data);
        if (Helpers::IsResponseStatusSuccess($roleChangingService)) {
            return true;
        }
        throw new Exception(join(" , ", Helpers::getResponseMessages($roleChangingService)));
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    private function setUserDepartment(array $data): self
    {
        if (!isset($data["department_id"])) {
            throw new Exception("Department ID Has Not Been Sent");
        }
        $this->user->department_id = $data["department_id"];
        if ($this->user->save()) {
            return $this;
        }
        throw  new Exception("Failed To Associate User To The Given Department !");
    }

    abstract protected function needToSetRoleAndDepartment(int $status): bool;
    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    private function setUserRelationships(array $data): self
    {
        if (!$this->needToSetRoleAndDepartment($data["status"])) {
            return $this;
        }
        $this->setUserRole($data);
        $this->setUserDepartment($data);
        return $this;
    }
    /**
     * @param int $status
     * @return $this
     * @throws Exception
     */
    private function changeUserStatus(int $status): self
    {
        $this->user->status = $status;
        if ($status !== 1) {
            $this->setDefaultUserType();
        } else {
            $this->setEmployeeUserType();
        }
        return $this;
    }

    /**
     * @param int $status
     * @return bool
     */
    private function sendStatusChangingNotification(int $status): bool
    {
        $this->user->notify(new (static::StatusNotificationMap[$status])());
        return true;
    }

    /**
     * @param array $data
     * @return JsonResponse
     * @throws Exception
     */
    protected function changerFun(array $data): JsonResponse
    {
        DB::beginTransaction();
        $this->changeUserStatus($data["status"])->setUserRelationships($data);
        if ($this->user->save()) {
            DB::commit();
            $this->sendStatusChangingNotification($data["status"]);
            return Response::success([], ["Account Status Changed Successfully"]);
        }
        return Response::error(["Failed To Change Account Status !"]);
    }

    protected function actionWithErrorResponding(): void
    {
        DB::rollBack();
    }
}