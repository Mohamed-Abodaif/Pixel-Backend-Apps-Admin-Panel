<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTerm extends BaseModel
{
    use HasFactory,SoftDeletes;

    protected $table="payment_terms";
    const ROUTE_PARAMETER_NAME = "term";
    protected $fillable =[
        'name', "status"
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

}