<?php

namespace App\Models;

use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EditRequest extends BaseModel
{
    use HasFactory, Calculations;
   
    protected $table = 'edit_requests_expenses';

    protected $fillable =[
        'created_by',
        'expense_id',
        'required_edit',
        'status',
        'requested_at',
        'done_at',
    ];

    protected $dates = array('created_at','updated_at');


   
    public function createdBy(){
        return $this->belongsTo(User::class)->select('id','name');
    }

    public function scopeCreatedBy($query)
    {
        $query->where('created_by', auth()->user()->id);

    }
    public function expense(){
        return $this->belongsTo(Expense::class);
    }
}
