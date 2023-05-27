<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends BaseModel
{
    use HasFactory, SoftDeletes, Calculations;

    protected $table = "currencies";
    const ROUTE_PARAMETER_NAME = "currency";
    protected $fillable = [
        'is_main',
        "status"
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    protected $casts = [
        'status' => 'boolean',
    ];
}
