<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyContact extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "company_contact";

    protected $fillable = [
        'company_id',
        'contact_No',
        'contact_No_type'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
