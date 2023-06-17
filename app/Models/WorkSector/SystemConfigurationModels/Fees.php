<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fees extends BaseModel
{
    use HasFactory,SoftDeletes;

    protected $table="fees";
    const ROUTE_PARAMETER_NAME = "taxes-official-fees";
    protected $fillable =[
        'name' , "status","percentage"
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    protected $casts = [
        'status'=>'boolean',
        'percentage'=>'float'
    ];
}