<?php

namespace App\Models\WorkSector\ClientsModule;

use App\Traits\Status;
use App\Models\BaseModel;

use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes; //Status

    protected $table = "clients";
    protected $casts = [
        'registration_no_attachment' => 'array',
        'taxes_no_attachment' => 'array',
        'exemption_attachment' => 'array',
        'sales_taxes_attachment' => 'array',
        'status' => 'boolean'
    ];

    protected $guarded = [];

    public function scopeActive()
    {
        return $this->where('status', 1);
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
