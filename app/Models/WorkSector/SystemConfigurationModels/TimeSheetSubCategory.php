<?php

namespace App\Models\WorkSector\SystemConfigurationModels;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeSheetSubCategory extends BaseModel
{
    use HasFactory;

    protected $table = "employees_timesheet_sub_categories";
    const ROUTE_PARAMETER_NAME = "subcategory";
    protected $fillable = [
        'name',
        'timesheet_category_id',
        'options',
        "status"
    ];

    protected $casts = [
        'status' => 'boolean',
        'timesheet_category_id' => 'integer'
    ];

    /**
     * Get/Set options
     *
     * @return Attribute
     */
    protected function options(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TimeSheetCategory::class, 'timesheet_category_id','id');
    }

    public function scopeActive()
    {
        return $this->where('status', true);
    }
}
