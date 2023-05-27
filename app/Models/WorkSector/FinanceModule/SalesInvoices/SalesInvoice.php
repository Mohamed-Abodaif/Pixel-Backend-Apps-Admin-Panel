<?php

namespace App\Models\WorkSector\FinanceModule\SalesInvoices;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SystemConfigurationModels\Currency;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SystemConfigurationModels\Department;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesInvoice extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = 'sales_invoices';
    protected $fillable = [
        'date',
        'payment_date',
        'electronic_sales_invoice_number',
        'sales_invoice_number',
        'sales_invoice_name',
        'client_id',
        'department_id',
        'currency_id',
        'invoice_value_without_taxes',
        'invoice_sales_taxes_value',
        'invoice_add_and_discount_taxes_value',
        'invoice_net_value',
        'paid_value',
        'rest_value',
        'invoice_attachments',
        'notes',
    ];

    protected $casts = [
        'invoice_attachments' => 'array',
        //        'invoice_sales_taxes_value'=>'float',
        //        'invoice_value_without_taxes'=>'float',
        //        'invoice_add_and_discount_taxes_value'=>'float'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->select('id', 'name');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->select('id', 'name');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->select('id', 'name', 'symbol');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalesInvoicePayment::class, 'sales_inviocie_id');
    }
}
