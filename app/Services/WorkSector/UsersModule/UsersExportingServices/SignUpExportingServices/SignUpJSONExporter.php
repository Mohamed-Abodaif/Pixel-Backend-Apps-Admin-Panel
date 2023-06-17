<?php

namespace App\Services\UserManagementServices\UsersExportingServices\SignUpExportingServices;


use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\JSONExporter\JSONExporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\FileExportingInterfaces\SupportRelationshipsFilesExporting;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class SignUpJSONExporter extends JSONExporter implements  SupportRelationshipsFilesExporting
{

    public function getModelRelationshipsFilesColumnsArray(): array
    {
        return ["profile" => ["avatar" , "national_id_files" , "passport_files"]];
    }

    protected function getModelClass(): string
    {
        return User::class;
    }

    protected function getModelSelectingQueryColumns(): array|null
    {
        return null;
    }


    protected function getWithRelationshipsArray(): array
    {
        return ['department:id,name' , 'profile' , 'profile.country:id,name','role:id,name' , "role.permissions:id,name"];
    }

    protected function getFiltersArray(): array
    {
        return [
            "created_at",
            "accepted_at",
            "status" ,
            AllowedFilter::callback('name', function (Builder $query, $value) {
                $query->where('first_name', 'LIKE', "%{$value}%")
                    ->orWhere('last_name', 'LIKE', "%{$value}%")
                    ->orWhere('mobile', 'LIKE', "%{$value}%")
                    ->orWhere('email', 'LIKE', "%{$value}%");
            })
        ];
    }

    protected function getDocumentTitle(): string
    {
        return "Sign Up Users";
    }

    protected function getModelDesiredFinalColumns(): array
    {
        return [];
    }

    protected function getRelationshipsDesiredFinalColumns(): array
    {
        return [
            'role' => ["columns" => ["name" , "id" , "permissions" => ["id" ] ] ] ,
            "profile" => [
                "columns" => [
                    "gender" , "passport_number" , "national_id_number" ,
                    "country" => []
                ],
                "columns_prefix" => ""
            ],
            "department" => [ ]
        ];
    }
}
