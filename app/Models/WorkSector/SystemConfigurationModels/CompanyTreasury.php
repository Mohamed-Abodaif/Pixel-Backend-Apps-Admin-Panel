<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyTreasury extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "company_treasuries";
    const ROUTE_PARAMETER_NAME = "company-treasuries";
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
