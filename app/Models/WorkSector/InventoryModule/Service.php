<?php

namespace App\Models\WorkSector\InventoryModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = 'services';
    protected $fillable = [
        'service_name',
        'department_id',
        'tools_certificates',
        'manual_attachment',
        'pictures',
        'description',
        'category_id',
    ];

    protected $casts = [
        'pictures' => 'array',
        'category_id' => 'integer',
        'department_id' => 'integer',
    ];
    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id')->select('id', 'name');
    }
    public function department()
    {
        return $this->belongsTo(Department::class)->select('id', 'name');
    }
}
