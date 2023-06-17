<?php

namespace App\Models\WorkSector\VendorsModule;

use App\Interfaces\HasStorageFolder;
use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVendorsPrice extends BaseModel implements HasStorageFolder 
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = 'product_vendors_prices';
    const ROUTE_PARAMETER_NAME = "client";

    protected $fillable = [
        'product_id',
        'vendor_id',
        'currency_id',
        'unit_id',
        'is_main',
        'cost_price',
    ];

    protected $casts = [
        'product_id' => 'integer',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->select('id', 'product_name');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id')->select('id', 'name');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id')->select('id', 'name', 'symbol');
    }
    public function unit()
    {
        return $this->belongsTo(MeasurementUnit::class, 'unit_id', 'id')->select('id', 'name');
    }

    public function getDocumentsStorageFolderName() : string
    {
        return "VendorOrdersFiles/" . $this->name;
    }
}
