<?php

namespace App\Services\WorkSector\UsersModule\UserCreatingService;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkSector\UsersModule\User;
use App\CustomLibs\APIResponder\APIResponder;
use App\CustomLibs\ValidatorLib\JSONValidator;
use App\Http\Requests\WorkSector\UsersModule\RegisterRequest;
use App\CustomLibs\CustomFileSystem\S3CustomFileSystem\CustomFileUploader\S3CustomFileUploader;
use App\Services\WorkSector\UsersModule\EmailVerificationService\VerificationNotificationSender;

class UserCreatingService
{

    use RespondersTrait, ValidationMethodsTrait, UserRelationshipsHandlerTrait;
    private $validator;
    private S3CustomFileUploader $customFileUploader;
    private APIResponder $APIResponder;

    public function __construct()
    {
        $this->customFileUploader = new S3CustomFileUploader();
    }

    /**
     * @param Request|array $request
     * @return self
     * @throws Exception
     */
    protected function initValidator($request): self
    {
        $this->validator = $request->all();
        return $this;
    }

    /**
     * @param array $data
     * @return User
     * @throws Exception
     */
    private function createUserAccount(array $data): User
    {
        $data["password"] = Hash::make($data["password"]);
        $user = User::create($data);
        if ($user == null) {
            throw new Exception("User Has Not Created !");
        }
        return $user;
    }

    /**
     * @param array $data
     * @param string $folderName
     * @return array
     */
    private function processAvatarFileInfo(array $data, string $folderName): array
    {
        $fileKey = "avatar";
        if (!isset($data[$fileKey])) {
            return $data;
        }
        return $this->customFileUploader->processFile($data, "avatar", $folderName . "/" . $fileKey);
    }

    /**
     * @param array $data
     * @param string $folderName
     * @return array
     * @throws Exception
     */
    private function processNationalIdFilesInfo(array $data, string $folderName): array
    {
        $fileKey = "national_id_files";
        if (!isset($data[$fileKey])) {
            return $data;
        }
        return $this->customFileUploader->processMultiUploadedFile($data, $fileKey, $folderName . "/" . $fileKey);
    }

    /**
     * @param array $data
     * @param string $folderName
     * @return array
     * @throws Exception
     */
    private function processPassportFilesInfo(array $data, string $folderName): array
    {
        $fileKey = "passport_files";
        if (!isset($data[$fileKey])) {
            return $data;
        }
        return $this->customFileUploader->processMultiUploadedFile($data, $fileKey, $folderName . "/" . $fileKey);
    }

    /**
     * @param User $user
     * @param array $data
     * @return array
     * @throws Exception
     */
    private function UserProfileFilesInfoGetter(User $user, array $data): array
    {
        $userFolderName = $user->getDocumentsStorageFolderName();

        //It will process the file and then set the fileKey value to filePath
        // (The uploaded File Object Will be saved in customFileSystem special property to upload it later)
        //No File Will be uploaded by processing .... Don't Forget to use uploading functions after Database Transaction is Commit
        $data = $this->processAvatarFileInfo($data, $userFolderName);
        $data = $this->processNationalIdFilesInfo($data, $userFolderName);
        return $this->processPassportFilesInfo($data, $userFolderName);
    }

//     private function sendEmailVerificationNotification(User $user): bool | JsonResponse
//     {
//         return (new VerificationNotificationSender())->setUser($user)->send();
//     }
    function sendEmailVerificationNotification(User $user)
    {
        $token = generateVerificationToken($user, 'email');
        $tokenLink = getVerificationLink($token, 'user');
        return sendEmailVerification($user, null, null, $tokenLink, 'email');
    }
    /**
     * @param User $user
     * @param array $data
     * @return Model
     * @throws Exception
     */
    private function createUserProfile(User $user, array $data): Model
    {
        $data = $this->UserProfileFilesInfoGetter($user, $data);
        $profile = $user->profile()->create($data);
        if ($profile == null) {
            throw new Exception("User Profile Has Not Created !");
        }
        return $profile;
    }

    /**
     * @return void
     */
    private function uploadAllFiles(): void
    {
        $this->customFileUploader->uploadFiles();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create($request): JsonResponse
    {
        try {
            //validation Operations
            $this->initValidator($request);
            $data = $this->validator;
            // $validationResult = $this->validateRequestData();
            if ($data instanceof JsonResponse) {
                return $data;
            }

            //Database operations
            DB::beginTransaction();

            //user's account
            $user = $this->createUserAccount($data);

            //user relationships
            $this->associateUserToRelationships($user, $data);
            $this->createUserProfile($user, $data);

            //If No Exception Is Thrown ... All Files Will Be Uploaded
            $this->uploadAllFiles();
            DB::commit();

            $this->sendEmailVerificationNotification($user);
            return $this->getSuccessResponse($user,['User Created Successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return  $this->getErrorResponse([$e->getMessage()]);
        }
    }
}
