<?php

namespace Database\Factories\User;
use App\CustomLibs\CustomFileSystem\CustomFileUploader;
use App\CustomLibs\CustomFileSystem\S3CustomFileSystem\CustomFileUploader\S3CustomFileUploader;
use App\Models\Country;
use App\Models\RoleModel;
use App\Models\SystemConfig\Department;
use App\Models\WorkSector\UsersModule\User;
use App\Models\WorkSector\UsersModule\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class UserProfileFactory extends Factory
{

    private CustomFileUploader $uploader;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null , User $user)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection);
        $this->user = $user;
        $this->uploader = new S3CustomFileUploader();
        $this->setCountryIDS();
    }

    private function fakeAvatarFile() : string
    {
        $fileKey = "avatar";
        $folder = $this->user->getDocumentsStorageFolderName() . $fileKey;
        return $this->uploader->fakeSingleFile($fileKey , $folder);
    }

    private function fakeNationalIdFiles() : string
    {
        $filesKey = "national_id_files";
        $folder = $this->user->getDocumentsStorageFolderName() . $filesKey;
        return json_encode($this->uploader->fakeMultiFiles($filesKey , $folder));
    }

    private function getPassportFiles() : string
    {
        $filesKey = "passport_files";
        $folder = $this->user->getDocumentsStorageFolderName() . $filesKey;
        return json_encode($this->uploader->fakeMultiFiles($filesKey , $folder));
    }


    protected array $genders = ["male" , "female"];

    protected array $countryIDS = [];
    protected int $countriesCount ;
    protected function setCountryIDS()
    {
        $this->countryIDS = Country::pluck("id")->toArray();
        $this->countriesCount = count($this->countryIDS);
    }

    protected $model = UserProfile::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $national_id_files = $this->fakeNationalIdFiles();
        $avatar = $this->fakeAvatarFile();
        $passport_files = $this->getPassportFiles();
        $this->uploader->uploadFiles();
        return [
            'gender' => $this->genders[$this->faker->numberBetween(0,1)],
            'national_id_number' => $this->faker->randomDigit(),
            'national_id_files' => $national_id_files,
            'passport_files' => $passport_files,
            'passport_number' => $this->faker->randomDigit(),
            'avatar' => $avatar,
            "country_id" => $this->countryIDS[rand(0 , $this->countriesCount)]
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }


    private array $RoleIDS = [];
    private int $RolesCount = 1;
    protected function setRoleIDS()
    {
        $this->RoleIDS = RoleModel::pluck("id")->toArray();
        $this->RolesCount = count($this->RoleIDS);
    }

    public function withRoleState() : Factory
    {
        $this->setRoleIDS();
        return $this->state(function(array $attributes){
            return [
                'role_id' => $this->RoleIDS[rand(0 , $this->RolesCount)] ?? null
            ];
        });
    }

    private array $DepartmentIDS = [];
    private int $DepartmentsCount = 1;
    protected function setDepartmentIDS()  : Factory
    {
        $this->DepartmentIDS = Department::pluck("id")->toArray();
        $this->DepartmentsCount = count($this->DepartmentIDS);
    }
    public function withDepartmentState()
    {
        $this->setDepartmentIDS();
        return $this->state(function(array $attributes){
            return [
                'department_id' => $this->DepartmentIDS[rand(0 , $this->DepartmentsCount)] ?? null
            ];
        });
    }
}
