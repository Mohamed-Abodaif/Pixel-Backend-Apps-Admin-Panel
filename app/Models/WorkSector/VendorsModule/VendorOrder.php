<?php

namespace App\Models\WorkSector\VendorsModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SystemConfigurationModels\Currency;
use App\Models\SystemConfigurationModels\Department;
use App\Models\SystemConfigurationModels\PaymentTerm;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorOrder extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = "vendor_orders";

    protected $fillable = [
        'order_name',
        'order_number',
        'date',
        'delivery_date',
        'vendor_id',
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


    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
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
