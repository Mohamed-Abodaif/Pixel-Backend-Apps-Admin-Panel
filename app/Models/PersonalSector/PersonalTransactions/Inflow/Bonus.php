<?php

namespace App\Models\PersonalSector\PersonalTransactions\Inflow;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SystemConfigurationModels\Currency;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SystemConfigurationModels\CustodySender;
use App\Services\CoreServices\CRUDServices\Interfaces\OwnsRelationships;

class Bonus extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;
    protected $casts = [
        'attachments' => 'array'
    ];
    protected $fillable = [
        'user_id',
        'amount',
        'currency_id',
        'received_from',
        'attachments',
        'notes'
    ];

    protected $dates = array('created_at', 'updated_at');

    public function custodySender(): BelongsTo
    {
        return $this->belongsTo(CustodySender::class, 'received_from')->select('id', 'name');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->select('id', 'name', 'symbol');
    }

    public function scopeCreatedBy($query)
    {
        $query->where('user_id', auth("api")->user()->id);
    }
}
