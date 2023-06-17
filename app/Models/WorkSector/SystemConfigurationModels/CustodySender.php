<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use App\Models\BaseModel;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustodySender extends BaseModel
{
    use HasFactory,SoftDeletes;

    protected $table="custody_senders";
    const ROUTE_PARAMETER_NAME = "sender";

    protected $fillable =[
        'name',
        'user_id',
        "status"
    ];

    protected $casts = [
        'status'=>'boolean'
    ];


    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
}