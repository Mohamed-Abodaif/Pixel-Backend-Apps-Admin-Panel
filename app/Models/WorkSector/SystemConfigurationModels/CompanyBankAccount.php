<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyBankAccount extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "company_bank_accounts";
    const ROUTE_PARAMETER_NAME = "companyBankAccount";
    protected $fillable = [
        "account_name", "bank_name", "bank_branch", "country_id", "city_id", "currency_id", "bank_code_type", "swift_code", "account_number", "iban_number", "status"
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    protected $casts = [
        'status' => 'boolean',
    ];
}
