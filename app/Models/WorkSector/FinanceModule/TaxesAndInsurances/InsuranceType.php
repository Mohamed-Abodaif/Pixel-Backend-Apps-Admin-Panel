<?php

namespace App\Models\WorkSector\FinanceModule\TaxesAndInsurances;

use App\Traits\Status;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InsuranceType extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "insurance_types";

    protected $fillable = [
        'name'
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    protected $casts = [
        'status' => 'boolean',
    ];
}
