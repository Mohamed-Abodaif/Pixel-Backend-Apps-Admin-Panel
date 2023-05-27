<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Traits\Status;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanySector extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "company_sectors";

    protected $fillable = [
        'name',
        'status'
    ];
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    protected $casts = [
        'status' => 'integer',
    ];
}
