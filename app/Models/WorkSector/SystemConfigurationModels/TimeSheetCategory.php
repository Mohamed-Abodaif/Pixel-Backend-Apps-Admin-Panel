<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeSheetCategory extends BaseModel
{
    use HasFactory;

    protected $table = "employees_timesheet_categories";
    const ROUTE_PARAMETER_NAME = "category";
    protected $fillable = [
        'name', "status"
    ];

    protected $casts = [
        'status' => 'boolean',
    ];


    public function scopeActive()
    {
        return $this->where('status', true);
    }

    public function subCategory(): HasMany
    {
        return $this->hasMany(TimeSheetSubCategory::class, 'timesheet_category_id', 'id')->select(['id', 'name', 'options', 'timesheet_category_id']);
    }
}
