<?php

namespace App\Models\WorkSector\ClientsModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientQuotationItem extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = "client_quotation_items";

    protected $fillable = [
        'client_quotation_id',
        'quantity_value',
        'quantity_option',
        'description',
        'unit_price',
        'total_price',
        'image',
    ];


    public function cleintQuotation()
    {
        return $this->belongsTo(ClientQuotation::class);
    }
}
