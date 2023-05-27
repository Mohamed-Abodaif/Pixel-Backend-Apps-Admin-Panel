<?php

namespace App\Models\PersonalSector\PersonalTransactions\Outflow;


use App\Models\BaseModel;
use App\Traits\Calculations;
use App\Models\SystemConfigurationModels\Currency;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SystemConfigurationModels\PaymentMethod;

class ExchangeExpense extends BaseModel
{
    use HasFactory, Calculations;

    protected $fillable = [
        'user_id',
        'exchange_date',
        'from_currency',
        'to_currency',
        'from_amount',
        'to_amount',
        'attachments',
        'notes',
        'exchange_rate',
        'exchange_place',
        'payment_method_id',
    ];


    protected $casts = [
        'attachments' => 'array',
        'from_amount' => 'double',
        'to_amount' => 'double',
        'exchange_rate' => 'double',
        'month' => 'array'
    ];


    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function fromCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'from_currency');
    }

    public function toCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'to_currency');
    }

    public function scopeCreatedBy($query)
    {
        $query->where('user_id', auth("api")->user()->id);
    }
}
