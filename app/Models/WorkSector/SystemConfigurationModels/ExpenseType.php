<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseType extends BaseModel
{
    use SoftDeletes, HasFactory;
    protected $table = "expense_types";
    protected $fillable = ['name', 'category', "status"];
    const ROUTE_PARAMETER_NAME = "type";
    const CATEGORY_TYPES = ['assets', 'company_operation', 'client_po', 'marketing', 'client_visits_preorders', 'purchase_for_inventory', 'taxes', 'insurances', 'exchange_currency'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetsCategory::class, 'category');
    }
}
