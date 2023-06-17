<?php

namespace App\Models\WorkSector\ClientsModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use App\Interfaces\HasStorageFolder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\WorkSector\SystemConfigurationModels\Currency;
use App\Models\WorkSector\SystemConfigurationModels\Department;
use App\Models\WorkSector\SystemConfigurationModels\PaymentTerm;
use App\Models\WorkSector\ClientsModule\Client;

class ClientQuotation extends BaseModel implements HasStorageFolder
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

    public function getDocumentsStorageFolderName(): string
    {
        return "ClientQuotatuinsFiles/" . $this->name;
    }
    public function getNamingPropertyName(): string
    {
        return "";
    }
}
