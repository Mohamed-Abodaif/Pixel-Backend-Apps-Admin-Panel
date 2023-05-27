<?php

namespace App\Models\WorkSector\ClientsModule;

use App\Traits\Status;
use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderType extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = "purchase_order_types";

    protected $fillable = [
        'name'
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    protected $casts = [
        'status' => 'boolean',
    ];
}
