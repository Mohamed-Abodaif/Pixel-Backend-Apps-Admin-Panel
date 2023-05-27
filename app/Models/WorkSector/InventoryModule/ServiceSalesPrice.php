<?php

namespace App\Models\WorkSector\InventoryModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceSalesPrice extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = 'service_sales_prices';
    protected $fillable = [
        'service_id',
        'currency_id',
        'is_main',
        'min_quantity',
        'max_quantity'
    ];

    protected $casts = [
        'service_id' => 'integer',
    ];
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id')->select('id', 'service_name');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id')->select('id', 'name');
    }
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id')->select('id', 'name', 'symbol');
    }
}
