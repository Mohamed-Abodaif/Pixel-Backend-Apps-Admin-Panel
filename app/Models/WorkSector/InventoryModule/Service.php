<?php

namespace App\Models\WorkSector\InventoryModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\CoreServices\CRUDServices\Interfaces\OwnsRelationships;

class Service extends BaseModel implements  OwnsRelationships
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
    public function salesPrices()
    {
        return $this->hasMany(ServiceSalesPrice::class, 'service_id');
    }

    public function vendorsPrices()
    {
        return $this->hasMany(ServiceVendorPrice::class, 'service_id');
    }
    public function getOwnedRelationshipNames() : array{
        return ["salesPrices","vendorsPrices"];
    }
}
