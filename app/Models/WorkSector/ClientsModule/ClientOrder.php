<?php

namespace App\Models\WorkSector\ClientsModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\WorkSector\ClientsModule\Client;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\WorkSector\SystemConfigurationModels\Currency;
use App\Models\WorkSector\SystemConfigurationModels\Department;
use App\Models\WorkSector\SystemConfigurationModels\PaymentTerm;

class ClientOrder extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = "client_orders";
    protected $casts = [
        //      'po_add_and_discount_taxes_value'=>'float',
        //      'po_total_value'=>'float',
        //      'po_sales_taxes_value'=>'float',
        //      'po_net_value'=>'float',
    ];
    protected $fillable = [
        'order_name',
        'date',
        'delivery_date',
        'client_id',
        'order_number',
        'department_id',
        'payments_terms_id',
        'currency_id',
        'po_total_value',
        'po_net_value',
        'po_sales_taxes_value',
        'po_add_and_discount_taxes_value',
        'po_attachments',
        'notes'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function paymentTerm(): BelongsTo
    {
        return $this->belongsTo(PaymentTerm::class, 'payments_terms_id')->select('id', 'name');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
