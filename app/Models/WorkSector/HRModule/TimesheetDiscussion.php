<?php

namespace App\Models\WorkSector\HRModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetDiscussion extends Model
{
    use HasFactory;

    protected $table = 'timesheet_discussions';

    protected $fillable = [
        'timesheet_id',
        'sender_id',
        'receiver_id',
        'message',
        'seen',
        'attachment'
    ];

    protected $casts = [
        'timesheet_id' => 'integer',
        'sender_id' => 'integer',
        'receiver_id' => 'integer',
        'seen' => 'integer'
    ];

    protected $dates = array('created_at', 'updated_at');

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id')->select('id', 'name', 'avatar');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id')->select('id', 'name', 'avatar');
    }

    public function timesheet()
    {
        return $this->belongsTo(EmployeeTimeSheet::class, 'timesheet_id');
    }
}
