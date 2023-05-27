<?php

namespace App\Services\WorkSector\UsersModule\UserDeletingService;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\WorkSector\UsersModule\User;
use App\CustomLibs\APIResponder\APIResponder;
use App\CustomLibs\CustomFileSystem\CustomFileDeleter;
use App\CustomLibs\CustomFileSystem\S3CustomFileSystem\CustomFileDeleter\S3CustomFileDeleter;


class UserDeletingService
{
    use RespondersTrait;
    private APIResponder $APIResponder;
    private CustomFileDeleter $customFileDeleter;

    public function __construct()
    {
        $this->customFileDeleter = new S3CustomFileDeleter();
    }

    /**
     * @param string $avatarPath
     * @return string|bool
     */
    private function deleteAvatarFile(string $avatarPath): string | bool
    {
        $this->customFileDeleter->deleteFileWithFolder($avatarPath);
        return true;
    }

    /**
     * @param array $NationalIdFiles
     * @return bool
     */
    private function deleteNationalIdFiles(array $NationalIdFiles): bool
    {
        return $this->deleteMultiFiles($NationalIdFiles, "National Id");
    }

    /**
     * @param array $PassportFiles
     * @return bool
     */
    private function deletePassportFiles(array $PassportFiles): bool
    {
        return $this->deleteMultiFiles($PassportFiles, "passport");
    }

    /**
     * @param array $filePaths
     * @return bool
     * @throws Exception
     */
    private function deleteMultiFiles(array $filePaths): bool
    {
        foreach ($filePaths as  $filePath) {
            $this->customFileDeleter->deleteFileByPath($filePath);
        }
        return true;
    }


    private function deleteAllUserTokens(User $user): bool
    {
        return $user->tokens()->delete();
    }

    /**
     * @param User $user
     * @return bool
     * @throws Exception
     */
    private function SoftlyDelete(User $user): bool
    {
        $this->deleteAllUserTokens($user);
        if ($user->delete()) {
            return true;
        }
        throw new Exception("Failed To Delete User !");
    }

    /**
     * @param User $user
     * @return bool
     * @throws Exception
     * Will Delete The User Permanently (With Its Files Otherwise Nothing Will Be Deleted Because OF Transaction 's Rollback)
     */
    private function ForcedDelete(User $user): bool
    {
        $userStorageFolder = $user->getDocumentsStorageFolderName();
        DB::beginTransaction();
        if ($user->forceDelete()) {
            if ($this->customFileDeleter->deleteFolder($userStorageFolder)) {
                DB::commit();
                return true;
            }
            DB::rollBack();
            throw new Exception("Failed To Delete User's Files !");
        }
        throw new Exception("Failed To Delete User !");
    }

    public function delete(User $user, bool $forcedDeleting = false): JsonResponse
    {
        try {
            if ($forcedDeleting ? $this->ForcedDelete($user) : $this->SoftlyDelete($user)) {
                return $this->getSuccessResponse();
            }
            return $this->getErrorResponse();
        } catch (Exception $e) {
            return $this->getErrorResponse([$e->getMessage()]);
        }
    }
}
