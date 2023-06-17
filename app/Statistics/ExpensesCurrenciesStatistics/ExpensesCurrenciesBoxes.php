<?php
namespace App\Statistics\ExpensesCurrenciesStatistics;

use App\Statistics\Interfaces\BoxesInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpensesCurrenciesBoxes implements BoxesInterface{
    public static function query($context_view){
        $query = DB::table('currencies')->join('currencies', 'expenses.currency_id', '=', 'currencies.id')
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