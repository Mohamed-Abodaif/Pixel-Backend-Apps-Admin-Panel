<?php

namespace App\Models\WorkSector\InventoryModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductLogistic extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = 'product_logistics';
    protected $fillable = [
        'product_id',
        'hs_code',
        'units_per_package',
        'expiry_time',
        'expiry_time_unit',
        'dimensions',
        'dimensions_unit',
        'weight',
        'weight_unit',
    ];

    protected $casts = [
        'product_id' => 'integer',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->select('id', 'product_name');
    }
}
