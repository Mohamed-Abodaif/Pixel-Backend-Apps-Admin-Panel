<?php

namespace App\Models\WorkSector\FinanceModule\PurchaseInvoices;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SystemConfigurationModels\Currency;
use App\Models\SystemConfigurationModels\Department;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseInvoice extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;
    protected $table = "purchase_invoices";
    protected $casts = [
        'invoice_attachments' => 'array',
        //        'invoice_value_without_taxes'=>'float',
        //        'invoice_sales_taxes_value'=>'float',
        //        'invoice_add_and_discount_taxes_value'=>'float',
    ];

    protected $fillable = [
        'date',
        'payment_date',
        'vendor_purchase_invoice_number',
        'purchase_invoice_name',
        'electronic_purchase_invoice_number',
        'vendor_id',
        'department_id',
        'currency_id',
        'invoice_value_without_taxes',
        'invoice_sales_taxes_value',
        'invoice_add_and_discount_taxes_value',
        'invoice_net_value',
        'invoice_attachments',
        'notes',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class)->select('id', 'name');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->select('id', 'name');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->select('id', 'name', 'symbol');
    }
}
