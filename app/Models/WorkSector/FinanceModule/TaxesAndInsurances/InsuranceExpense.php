<?php

namespace App\Models\WorkSector\FinanceModule\TaxesAndInsurances;

use App\Models\BaseModel;
use App\Traits\Calculations;
use App\Models\SystemConfigurationModels\Tender;
use App\Models\SystemConfigurationModels\Currency;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SystemConfigurationModels\PaymentMethod;

class InsuranceExpense extends BaseModel
{
    use HasFactory, Calculations;

    protected $table = "insurance_expenses";
    protected $fillable = [
        'user_id',
        'payment_date',
        'amount',
        'paid_to',
        'attachments',
        'notes',
        'insurance_duration',
        'months_list',
        'insurance_reference_number',
        'tender_insurance_percentage',
        'tender_estimated_date',
        'final_refund_date',
        'type',
        'tender_insurance_type',
        'client_id',
        'asset_id',
        'tender_id',
        'currency_id',
        'payment_method_id',
        'purchase_invoice_id',
    ];


    protected $casts = [
        'attachments' => 'array',
        'amount' => 'double',
    ];


    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    public function purchaseInvoice(): BelongsTo
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function clientOrder(): BelongsTo
    {
        return $this->belongsTo(ClientOrder::class, 'client_po_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function scopeCreatedBy($query)
    {
        $query->where('user_id', auth("api")->user()->id);
    }
}
