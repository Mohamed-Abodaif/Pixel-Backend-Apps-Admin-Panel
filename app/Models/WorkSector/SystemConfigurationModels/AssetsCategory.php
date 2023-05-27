<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetsCategory extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "assets_categories";

    const ROUTE_PARAMETER_NAME = "category";
    protected $fillable = [
        'name', "status"
    ];
    protected $casts = [
        'status' => 'boolean'
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1)->orderBy('created_at', 'desc');;
    }
}
