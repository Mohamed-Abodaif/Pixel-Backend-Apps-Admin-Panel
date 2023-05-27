<?php

namespace App\Models\WorkSector\FinanceModule\SalesInvoices;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SystemConfigurationModels\Currency;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SystemConfigurationModels\PaymentMethod;

class SalesInvoicePayment extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "sales_invoice_payments";

    protected $fillable = [
        'value',
        'amount_before',
        'sales_invoice_id ',
        'payment_method_id',
        'currency_id'
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->select('id', 'name', 'symbol');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class)->select('id', 'name', 'symbol');
    }

    public function salesInvoice(): BelongsTo
    {
        return $this->belongsTo(salesInvoice::class)->select('id', 'sales_invoice_number');
    }
}
