<?php

namespace App\Models\WorkSector\FinanceModule\AssetsList;


use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SystemConfigurationModels\AssetsCategory;
use App\Models\WorkSector\SystemConfigurationModels\Department;

class Asset extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = "assets";
    protected $casts = [
        'asset_documents' => 'array',
        //'buying_date'=>'date:d-m-Y',

    ];
    protected $fillable = [
        'asset_name',
        'asset_description',
        'picture',
        'buying_date',
        'department_id',
        'asset_category_id',
        'asset_documents',
        'notes'
    ];

    public $timestamps = true;

    // public function setBuyingDateAttribute($value)
    //{
    //     $this->attributes['buying_date'] = (new Carbon($value))->format('Y-m-d');
    // }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->select('id', 'name');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetsCategory::class, 'asset_category_id')->select('id', 'name');
    }
}
