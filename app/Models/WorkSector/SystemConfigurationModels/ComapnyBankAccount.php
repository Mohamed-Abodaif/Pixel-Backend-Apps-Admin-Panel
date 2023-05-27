<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComapnyBankAccount extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "company_bank_accounts";
    const ROUTE_PARAMETER_NAME = "company_bank_accounts";
    protected $fillable = [
        'name', "status"
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    protected $casts = [
        'status' => 'boolean',
    ];
}
