<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyTreasury extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = "company_treasuries";
    const ROUTE_PARAMETER_NAME = "companyTreasury";
    protected $fillable = [
        'name','currency_id','user_id','branch_id', 'status'
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    protected $casts = [
        'status' => 'boolean',
        'branch_id' => 'integer',
        'currency_id' => 'integer',
        'user_id' => 'integer'
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
    public function person(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
