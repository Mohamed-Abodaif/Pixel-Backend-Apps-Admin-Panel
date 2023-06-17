<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = "departments";
    const ROUTE_PARAMETER_NAME = "department";
    protected $fillable = [
        "name", "department_type","parent_id","status"
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    protected $casts = [
        'status' => 'boolean',
        'parent_id' => 'integer'
    ];

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }
}
