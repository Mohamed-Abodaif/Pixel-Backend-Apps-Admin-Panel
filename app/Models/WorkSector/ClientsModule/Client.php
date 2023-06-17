<?php

namespace App\Models\WorkSector\ClientsModule;

use App\Interfaces\HasStorageFolder;
use App\Interfaces\HasUUID;
use App\Models\BaseModel;
use App\Models\WorkSector\Country\Country;
use App\Services\CoreServices\CRUDServices\Interfaces\OwnsRelationships;
use App\Services\CoreServices\CRUDServices\Interfaces\ParticipatesToRelationships;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Client extends BaseModel implements HasStorageFolder, OwnsRelationships , HasUUID
{
    use HasFactory, Calculations, SoftDeletes; //Status

    protected $table = "clients";
    const ROUTE_PARAMETER_NAME = "client";

    protected $fillable = [
        "hashed_id",
        "name",
        "client_type",
        "logo",
        "status",
        'registration_no',
        'registration_no_attachment',
        'taxes_no',
        'taxes_no_attachment',
        'exemption_attachment',
        'sales_taxes_attachment',
    ];

    /**
     * @return string[]
     * These Relationships Is Used Only For Storing And Updating Services
     */
    public function getOwnedRelationshipNames(): array
    {
        return ["addresses", "attachments"];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function addresses() : HasMany
    {
        return $this->hasMany(ClientAddress::class);
    }

    //    public function contacts()
    //    {
    //        return $this->hasManyThrough(ClientContact::class,ClientAddress::class,'client_id',"client_address_id");
    //    }

    public function attachments() :HasMany
    {
        return $this->hasMany(ClientAttachment::class);
    }

    public function getDocumentsStorageFolderName(): string
    {
        /** $this->hashed_id 's Value Is Set In BaseModel When Child Model Is Implementing HasUUID Interface */
        return "ClientsFiles/" . $this->hashed_id;
    }
    // public function city()
    // {
    //     return $this->belongsTo(City::class);
    // }
    // public function companySectors()
    // {
    //     return $this->belongsToMany(CompanySector::class);
    // }


    // protected function type(): Attribute
    // {
    //     return new Attribute(
    //         get: function ($value) {
    //             return match ($value) {
    //                 1 => ["label" => "Free Zone", "value" => $value],
    //                 2 => ["label" => "Local", "value" => $value],
    //                 3 => ["label" => "International", "value" => $value]
    //             };
    //         },
    //         set: function ($value) {
    //             return match (strtolower($value)) {
    //                 'free zone' => 1,
    //                 'local' => 2,
    //                 'international' =>  3,
    //             };
    //         }
    //     );
    // }

    // public function sites()
    // {
    //     return $this->morphMany(Site::class, 'siteable');
    // }
    // public function branchInformation()
    // {
    //     return $this->morphMany(BranchInformation::class, 'branchable');
    // }
    // public function bankAccounts()
    // {
    //     return $this->morphMany(BankAccount::class, 'bank_accountable');
    // }

}
