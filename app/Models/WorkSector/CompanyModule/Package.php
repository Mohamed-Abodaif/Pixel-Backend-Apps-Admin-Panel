<?php

namespace App\Models\WorkSector\CompanyModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'show',
        'monthly_price',
        'annual_price',
        'monthly_discount',
        'annual_discount',
        'privileges'
    ];
}
