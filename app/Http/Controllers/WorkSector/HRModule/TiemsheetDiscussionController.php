<?php

namespace App\Http\Controllers\WorkSector\HRModule;

use Carbon\Carbon;
use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\WorkSector\HRModule\EmployeeTimeSheet;
use App\Models\WorkSector\HRModule\TimesheetDiscussion;
use App\Http\Requests\WorkSector\HRModule\TimesheetDiscussionRequest;

class TiemsheetDiscussionController extends Controller
{
    public function messages(int $id)
    {
        $data = QueryBuilder::for(TimesheetDiscussion::class)
            ->where('timesheet_id', $id)
            ->with(['sender', 'receiver'])
            ->customOrdering('created_at', 'ASC')
            ->paginate(request()->pageSize ?? 10);
        TimesheetDiscussion::where('receiver_id', auth()->user()->id)->where('timesheet_id', $id)->update(['seen' => 1]);
        return response()->success([
            'messages' => $data,
            'count' => 20
        ]);
    }

    public function sendMessage(TimesheetDiscussionRequest $request, $id)
    {
        //
        $message = $request->get('message');
        $sender_id = auth()->user()->id;
        $result = EmployeeTimeSheet::select('user_id')->where('id', $id)->first();
        //
        TimesheetDiscussion::create([
            'message' => $message,
            'receiver_id' => $result['user_id'],
            'sender_id' => $sender_id,
            'timesheet_id' => $id
        ]);

        $response = [
            "message" => "Created Successfully",
            "status" => "success",
            "data" => $message
        ];

        return response()->json($response, 200);
    }

    public function markAsRead()
    {
        //TODO: mark all messages as read for specific expense
    }
}
