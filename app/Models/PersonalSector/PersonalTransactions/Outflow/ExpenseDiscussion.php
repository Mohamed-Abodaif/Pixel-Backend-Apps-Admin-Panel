<?php

namespace App\Models\PersonalSector\PersonalTransactions\Outflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseDiscussion extends Model
{
    use HasFactory;

    protected $table = 'expense_discussions';

    protected $fillable = [
        'expense_id',
        'sender_id',
        'receiver_id',
        'message',
        'attachment'
    ];

    protected $casts = [
        'seen' => 'boolean'
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')->select('id', 'name', 'avatar');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id')->select('id', 'name', 'avatar');
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }
}
