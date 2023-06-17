<?php
namespace App\Statistics\ExpensesStatistics;

use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Statistics\Interfaces\BoxesInterface;

class ExpensesBoxes implements BoxesInterface{
    public static function query($context_view){
        $query = DB::table('currencies')->join('currencies', 'expenses.currency_id', '=', 'currencies.id')
        ->where('user_id', auth()->user()->id)
        ->select([
            'currencies.name',
            'currencies.code',
            'currencies.symbol',
            DB::raw("COUNT(expenses.currency_id) as 'Total Records'"),
            DB::raw("COUNT(expenses.currency_id) as 'Total Added Records'"),
            //amounts
            DB::raw("CONCAT(CAST(SUM(expenses.amount) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Expenses'"),
            DB::raw("CONCAT(CAST(SUM(expenses.amount) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Added Expenses'")
        ])->groupBy('expenses.currency_id');
        return $query;
    }
}