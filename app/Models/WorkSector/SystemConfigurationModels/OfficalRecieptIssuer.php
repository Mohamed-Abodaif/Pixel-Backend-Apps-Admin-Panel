<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficalRecieptIssuer extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "offical_reciept_issuers";
    const ROUTE_PARAMETER_NAME = "offical-receipt-issuers";
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
