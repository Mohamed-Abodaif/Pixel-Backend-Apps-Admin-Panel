<?php

namespace App\Http\Controllers\PersonalSector\PersonalTransactions\OutFlow;

use Carbon\Carbon;
use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\WorkSector\SystemConfigurationModels\Expense;
use App\Models\WorkSector\SystemConfigurationModels\ExpenseDiscussion;
use App\Http\Requests\WorkSector\SystemConfigurations\ExpenseTypes\ExpenseDiscussionRequest;

class ExpenseDiscussionController extends Controller
{
    public function messages(int $id)
    {
        $data = QueryBuilder::for(ExpenseDiscussion::class)
            ->where('expense_id', $id)
            ->with(['sender', 'receiver'])
            ->customOrdering('created_at', 'ASC')
            ->paginate(request()->pageSize ?? 10);
        ExpenseDiscussion::where('receiver_id', auth()->user()->id)->where('expense_id', $id)->update(['seen' => 1]);
        return response()->success([
            'messages' => $data,
            'count' => 20
        ]);
    }

    public function sendMessage(ExpenseDiscussionRequest $request, $id)
    {
        //
        $message = $request->get('message');
        $sender_id = auth()->user()->id;
        $result = Expense::select('user_id')->where('id', $id)->first();
        //
        ExpenseDiscussion::create([
            'message' => $message,
            'receiver_id' => $result['user_id'],
            'sender_id' => $sender_id,
            'expense_id' => $id
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
