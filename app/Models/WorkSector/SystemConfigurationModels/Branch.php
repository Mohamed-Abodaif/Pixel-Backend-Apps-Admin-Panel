<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = "branches";
    const ROUTE_PARAMETER_NAME = "branch";
    protected $fillable = [
        "name", "branch_type","parent_id","status"
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
        return $this->belongsTo(Branch::class, 'parent_id');
    }
}
