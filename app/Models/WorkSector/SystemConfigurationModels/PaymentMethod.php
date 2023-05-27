<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "payment_methods";
    const ROUTE_PARAMETER_NAME = "method";
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
