<?php

namespace App\Services\WorkSector\UsersModule\UserProfileUpdatingService;

use Exception;
use App\Helpers\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Contracts\Auth\Authenticatable;
use App\CustomLibs\CustomFileSystem\CustomFileUploader;
use App\Services\WorkSector\UsersModule\UserUpdatingService;
use App\CustomLibs\CustomFileSystem\S3CustomFileSystem\CustomFileUploader\S3CustomFileUploader;
use App\Services\WorkSector\UsersModule\EmailVerificationService\VerificationNotificationSender;

class UserProfileUpdatingService extends UserUpdatingService
{

    use UserProfileFilesHandler;
    private CustomFileUploader $customFileUploader;
    private bool $needToNewVerification = false;

    public function __construct(User | Authenticatable $user)
    {
        parent::__construct($user);
        $this->customFileUploader = new S3CustomFileUploader();
    }

    public function getRequestKeysToValidation(): array
    {
        $this->validator->AllRules();
        return ["allRules"];
    }

    private function checkUserEmailChanging(array $data)
    {
        if (!isset($data["email"])) {
            return;
        }

        if ($this->user->email != $data["email"]) {
            $this->needToNewVerification = true;
        }
    }

    private function PasswordValueHandler(array $data): array
    {
        if (!isset($data["password"])) {
            return $data;
        }
        $data["password"] = Hash::make($data["password"]);
        return $data;
    }
    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    private function updateUser(array $data): self
    {
        $this->checkUserEmailChanging($data);
        $data = $this->PasswordValueHandler($data);
        if ($this->user->update($data)) {
            return $this;
        }
        throw new Exception("Failed To Update User Info !");
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    private function updateUserRelationships(array $data): self
    {
        return $this;
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    private function updateUserProfile(array $data): bool
    {
        $userProfile = $this->user->profile;
        $data = $this->UserProfileFilesInfoGetter($userProfile, $data);
        if ($userProfile->update($data)) {
            return true;
        }
        throw new Exception("Failed To Update User Profile's Info !");
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function sendEmailVerificationNotification(): bool
    {
        $sendingResult = (new VerificationNotificationSender())->setUser($this->user)->send();
        if (Helpers::IsResponseStatusSuccess($sendingResult)) {
            return true;
        }
        throw new Exception("User Updated Successfully But It Failed To Send Verification Token To The Updated User's Email !");
    }

    /**
     * @param array $data
     * @return JsonResponse
     * @throws Exception
     */
    protected function changerFun(array $data): JsonResponse
    {
        DB::beginTransaction();
        $this->updateUser($data)->updateUserRelationships($data)->updateUserProfile($data);

        //If No Exception Is Thrown Nothing Will Be Uploaded
        //if no files is uploaded .... there is nothing will be uploaded
        $this->uploadFiles();

        //If File Uploading has throw an Exception ... Nothing will be commit
        DB::commit();

        if ($this->needToNewVerification) {
            $this->sendEmailVerificationNotification();
            return Response::success([], ["User Updated Successfully!"], 201);
        }

        //if no exception is thrown = every thing is ok and function get successful
        return Response::success([], ["User Updated Successfully!"], 201);
    }
}
