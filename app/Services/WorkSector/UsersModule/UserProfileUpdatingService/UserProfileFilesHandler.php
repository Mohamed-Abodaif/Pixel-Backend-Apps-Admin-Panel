<?php

namespace App\Services\WorkSector\UsersModule\UserProfileUpdatingService;

use Exception;
use App\Models\WorkSector\UsersModule\UserProfile;

trait UserProfileFilesHandler
{
    private function getUserFolder(string $subFolderName): string
    {
        return $this->user->getDocumentsStorageFolderName() . "/" . str_replace("/", "", $subFolderName);
    }

    //Where File Is Uploaded During The Creation Operation
    private function processSingleFoundFile(array $data, string $fileKey, string $path): array
    {
        if (!isset($data[$fileKey])) {
            return $data;
        }
        $this->customFileUploader->makeFileReadyToStore($path,  $data[$fileKey]);
        $data[$fileKey] = $path;
        return $data;
    }

    /**
     * @param array $data
     * @param string $fileKey
     * @return array
     * @throws Exception
     * Where File Has Not Uploaded During The Creation Operation (When File Can Be Null) And Then Uploading It During The Updating Operation
     */
    private function processSingleNewUploadedFile(array $data, string $fileKey): array
    {
        if (!isset($data[$fileKey])) {
            return $data;
        }
        $userFolderName = $this->getUserFolder($fileKey);
        return $this->customFileUploader->processFile($data, $fileKey,  $userFolderName);
    }

    /**
     * @param UserProfile $userProfile
     * @param array $data
     * @param string $FilesKey
     * @return array
     */
    private function processMultiFoundFiles(UserProfile $userProfile, array $data, string $FilesKey): array
    {
        if (!isset($data[$FilesKey])) {
            return $data;
        }
        $fileNames = $userProfile->{$FilesKey};

        foreach ($data[$FilesKey] as $index => $uploadedFile) {
            $this->customFileUploader->makeFileReadyToStore($fileNames[$index],  $uploadedFile);
        }
        $data[$FilesKey] = $fileNames;
        return $data;
    }

    /**
     * @param UserProfile $userProfile
     * @param array $data
     * @param string $FilesKey
     * @return array
     * @throws Exception
     */
    private function processMultiNewUploadedFiles(UserProfile $userProfile, array $data, string $FilesKey): array
    {
        if (!isset($data[$FilesKey])) {
            return $data;
        }
        $userFolderName = $this->getUserFolder($FilesKey);
        return $this->customFileUploader->processMultiUploadedFile($data, $FilesKey,  $userFolderName);
    }

    /**
     * @param UserProfile $userProfile
     * @param array $data
     * @return array
     * @throws Exception
     *
     * Avatar Maybe null during the storing a new user ..... so we maybe don't have any path of avatar
     * so if a new avatar has been uploaded ... we must get a new path
     */
    private function processAvatarFile(UserProfile $userProfile, array $data): array
    {
        $FileKey = "avatar";
        return  $userProfile->avatar == null ?
            $this->processSingleNewUploadedFile($data, $FileKey) :
            $this->processSingleFoundFile($data, $FileKey, $userProfile->avatar);
    }

    /**
     * @param UserProfile $userProfile
     * @param array $data
     * @return array
     * @throws Exception
     *
     * National ID files Maybe null during the storing a new user ..... so we maybe don't have any path of National ID's files
     * so if new National ID Files have been uploaded ... we must get a new path
     */
    private function processNationalIdFiles(UserProfile $userProfile, array $data): array
    {
        $FileKey = "national_id_files";
        return  $userProfile->national_id_files == null ?
            $this->processMultiNewUploadedFiles($userProfile, $data, $FileKey) :
            $this->processMultiFoundFiles($userProfile, $data, $FileKey);
    }

    /**
     * @param UserProfile $userProfile
     * @param array $data
     * @return array
     * @throws Exception
     *
     * Passport files Maybe null during the storing a new user ..... so we maybe don't have any path of Passport's files
     * so if new Passport Files have been uploaded ... we must get a new path
     */
    private function processPassportFiles(UserProfile $userProfile, array $data): array
    {
        $FileKey = "passport_files";
        return  $userProfile->passport_files == null ?
            $this->processMultiNewUploadedFiles($userProfile, $data, $FileKey) :
            $this->processMultiFoundFiles($userProfile, $data, $FileKey);
    }


    /**
     * @param UserProfile $userProfile
     * @param array $data
     * @return array
     * @throws Exception
     *
     * processing all profile files
     */
    private function UserProfileFilesInfoGetter(UserProfile $userProfile, array $data): array
    {
        $data               = $this->processAvatarFile($userProfile, $data);
        $data               = $this->processNationalIdFiles($userProfile, $data);
        return                $this->processPassportFiles($userProfile, $data);
    }


    /**
     * @return bool
     */
    private function uploadFiles(): bool
    {
        //if no files is uploaded .... there is nothing will be uploaded
        return $this->customFileUploader->uploadFiles();
    }
}
