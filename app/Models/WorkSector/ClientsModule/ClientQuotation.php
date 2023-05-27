<?php

namespace App\Models\WorkSector\ClientsModule;


use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SystemConfigurationModels\Currency;
use App\Models\SystemConfigurationModels\Department;
use App\Models\SystemConfigurationModels\PaymentTerm;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientQuotation extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = "client_quotations";

    protected $fillable = [
        'date',
        'due_date',
        'client_id',
        'quotation_number',
        'quotation_name',
        'department_id',
        'payments_terms_id',
        'currency_id',
        'quotation_net_value',
        'quotation_attachments',
        'notes'
    ];

    protected $casts = [
        //        'quotation_net_value'=>'float',
        'quotation_attachments' => 'array',

    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->select('id', 'name');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->select('id', 'name', 'symbol');
    }

    public function paymentTerm(): BelongsTo
    {
        return $this->belongsTo(PaymentTerm::class, 'payments_terms_id')->select('id', 'name');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->select('id', 'name');
    }
}
