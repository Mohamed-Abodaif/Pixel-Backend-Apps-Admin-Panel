<?php

namespace App\Models\WorkSector\FinanceModule\CompanyTransactions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComapnyTransInflow extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        "created_at" => "datetime:M D ,Y",
    ];
}
