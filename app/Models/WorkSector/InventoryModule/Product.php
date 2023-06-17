<?php

namespace App\Models\WorkSector\InventoryModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\CoreServices\CRUDServices\Interfaces\OwnsRelationships;

class Product extends BaseModel implements  OwnsRelationships
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'product_name',
        'department_id',
        'category_id',
        'product_condition',
        'datasheet_attachment',
        'manual_attachment',
        'msds_attachment',
        'pictures',
    ];

    protected $casts = [
        'pictures' => 'array',
        'category_id' => 'integer',
        'department_id' => 'integer',
    ];
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id')->select('id', 'name');
    }
    public function department()
    {
        return $this->belongsTo(Department::class)->select('id', 'name');
    }
    public function logistics()
    { 
        return $this->hasMany(ProductLogistic::class, 'product_id');
    }
    public function salesPrices()
    {
        return $this->hasMany(ProductSalesPrice::class, 'product_id');
    }
    public function vendorsPrices()
    {
        return $this->hasMany(ProductVendorsPrice::class, 'product_id');
    }
    public function getOwnedRelationshipNames() : array{
        return ["logistics","salesPrices","vendorsPrices"];
    }

}
