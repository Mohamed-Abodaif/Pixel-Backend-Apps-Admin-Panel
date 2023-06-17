<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Traits\Status;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceCategory extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "service_categories";

    const ROUTE_PARAMETER_NAME = "category";

    protected $fillable = [
        'name',
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
