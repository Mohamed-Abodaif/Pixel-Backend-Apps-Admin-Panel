<?php

namespace App\Models\WorkSector\FinanceModule\CompanyTransactions;


use Illuminate\Database\Eloquent\Model;
use App\Models\WorkSector\UsersModule\User;
use App\Models\WorkSector\ClientsModule\Client;
use App\Models\WorkSector\VendorsModule\Vendor;
use App\Models\WorkSector\ClientsModule\ClientOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\WorkSector\SystemConfigurationModels\Currency;
use App\Models\WorkSector\SystemConfigurationModels\ExpenseType;
use App\Models\WorkSector\SystemConfigurationModels\CompanyTreasury;
use App\Models\WorkSector\SystemConfigurationModels\CompanyBankAccount;
use App\Models\WorkSector\FinanceModule\PurchaseInvoices\PurchaseInvoice;

class ComapnyTransOutflow extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        "created_at" => "datetime:M D ,Y",
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name');
    }
    public function bank()
    {
        return $this->belongsTo(CompanyBankAccount::class, "bank_id")->select('id', 'name', 'status');
    }

    public function treasury()
    {
        return $this->belongsTo(CompanyTreasury::class, "treasury_id");
    }

    public function currancy()
    {
        return $this->belongsTo(Currency::class);
    }

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class, "expense_type_id");
    }

    public function employee()
    {
        return $this->belongsTo(User::class, "employee_id")->select('id', 'name');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function clientOrder()
    {
        return $this->belongsTo(ClientOrder::class, "client_order_id");
    }

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, "purchase_invoice_id");
    }


    // ---------------------------- Scopes ---------------------------------
    public function scopeCash($query)
    {
        return $query->where('payment_method_type', 'Cash at Bank');
    }

    public function scopeBankCheque($query)
    {
        return $query->where('payment_method_type', 'Bank Cheque');
    }

    public function scopeBankTransfer($query)
    {
        return $query->where('payment_method_type', 'Bank Transfer');
    }

    public function scopeCardOffline($query)
    {
        return $query->where('payment_method_type', 'Via Card Online/Offline');
    }
}
