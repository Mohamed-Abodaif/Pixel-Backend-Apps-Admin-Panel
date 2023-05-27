<?php

namespace App\Models\WorkSector\InventoryModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSalesPrice extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = 'product_sales_prices';
    protected $fillable = [
        'product_id',
        'currency_id',
        'unit_id',
        'is_main',
        'min_quantity',
        'max_quantity',
        'cost_price',
    ];

    protected $casts = [
        'product_id' => 'integer',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->select('id', 'product_name');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id')->select('id', 'name', 'symbol');
    }
    public function unit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'unit_id', 'id')->select('id', 'name');
    }
}
