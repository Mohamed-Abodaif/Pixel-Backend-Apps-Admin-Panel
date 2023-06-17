<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Traits\Status;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeasurementUnit extends BaseModel
{
    use HasFactory;

    protected $table = "measurement_unites";
    const ROUTE_PARAMETER_NAME = "measurementUnit";

    protected $fillable = [
        'name',
        'category',
        'status'
    ];
    protected $casts = [
        'status' => 'integer'
    ];


    protected $dates = array('created_at', 'updated_at');

    public function scopeActive($query)
    {
        $query->where('status', 1)->orderBy('created_at', 'desc');;
    }
}
