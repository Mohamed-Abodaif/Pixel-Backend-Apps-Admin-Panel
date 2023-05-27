<?php

namespace App\Models\WorkSector\ClientsModule;

use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseRequest extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = 'purchase_requests';
    protected $fillable = [
        'title',
        'date',
        'pr_duedate',
        'department_id',
        'client_id',
        'has_attachment',
        'pr_number',
        'pr_attachment',
        'pr_requirements',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'department_id' => 'integer',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class)->select('id', 'name');
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
