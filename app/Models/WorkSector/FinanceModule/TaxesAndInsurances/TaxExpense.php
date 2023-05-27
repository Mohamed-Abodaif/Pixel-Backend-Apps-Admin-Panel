<?php

namespace App\Models\WorkSector\FinanceModule\TaxesAndInsurances;

use App\Models\BaseModel;
use App\Traits\Calculations;
use App\Models\SystemConfigurationModels\TaxType;
use App\Models\SystemConfigurationModels\Currency;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SystemConfigurationModels\PaymentMethod;

class TaxExpense extends BaseModel
{
    use HasFactory, Calculations;

    protected $table = 'tax_expenses';

    protected $fillable = [
        'user_id',
        'payment_date',
        'attachments',
        'notes',
        'amount',
        'paid_to',
        'years_list',
        'months_list',
        'tax_percentage',
        'type',
        'tax_type_id',
        'currency_id',
        'payment_method_id'
    ];

    protected $casts = [
        'attachments' => 'array',
        'amount' => 'double',
        'tax_percentage' => 'double',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id')->select('id', 'name', 'symbol');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id')->select('id', 'name');
    }

    public function taxType(): BelongsTo
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id')->select('id', 'name');
    }

    public function scopeCreatedBy($query)
    {
        $query->where('user_id', auth("api")->user()->id);
    }
}
