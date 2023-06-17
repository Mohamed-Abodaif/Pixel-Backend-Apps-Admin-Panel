<?php

namespace App\Models\PersonalSector\PersonalTransactions\Outflow;

use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\EditRequest;
use App\Traits\Calculations;
use App\Models\WorkSector\UsersModule\User;
use App\Models\WorkSector\ClientsModule\Client;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\WorkSector\ClientsModule\ClientOrder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\WorkSector\FinanceModule\AssetsList\Asset;
use App\Models\WorkSector\SystemConfigurationModels\Currency;
use App\Models\WorkSector\SystemConfigurationModels\ExpenseType;
use App\Models\WorkSector\SystemConfigurationModels\PaymentMethod;
use App\Models\WorkSector\FinanceModule\PurchaseInvoices\PurchaseInvoice;
use App\Models\PersonalSector\PersonalTransactions\Outflow\ExpenseDiscussion;

class Expense extends BaseModel
{
    use HasFactory, Calculations;

    protected $fillable = [
        'user_id',
        "payment_date",
        "attachments",
        "notes",
        "amount",
        "paid_to",
        "category",
        "asset_id",
        "client_id",
        "client_po_id",
        "purchase_invoice_id",
        "expense_type_id",
        "currency_id",
        "payment_method_id",
        'expense_invoice',
        'accepted_at',
        'accepted_by',
        'rejected_at',
        'rejected_by',
    ];

    protected $casts = [
        'attachments' => 'array',
        'amount' => 'double',
        'payment_date' => 'date',
    ];

    protected $dateFormat = 'Y-m-d';

    public function setPaymentDateAttribute($value)
    {
        $this->attributes['payment_date'] = (new Carbon($value))->format('y-m-d');
    }

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
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

    public function scopePending($query)
    {
        $query->where('status', 'Pending');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->select('name');
    }

    public function editRequests(): HasMany
    {
        return $this->hasMany(EditRequest::class, 'expense_id');
    }

    public function disscussions(): HasMany
    {
        return $this->hasMany(ExpenseDiscussion::class, 'expense_id');
    }
}
