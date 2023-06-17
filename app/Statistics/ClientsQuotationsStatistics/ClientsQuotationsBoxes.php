<?php
namespace App\Statistics\ClientsQuotationsStatistics;

use App\Statistics\Interfaces\BoxesInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientsQuotationsBoxes implements BoxesInterface{
    public static function query($context_view){
        $query = DB::table('currencies')->join('currencies', 'client_quotations.currency_id', '=', 'currencies.id')
        ->select([
            'currencies.name',
            'currencies.code',
            'currencies.symbol',
            DB::raw("COUNT(client_quotations.currency_id) as 'Total Records'"),
            DB::raw("COUNT(client_quotations.currency_id) as 'Total Added Records'"),
            //amounts
            DB::raw("CONCAT(CAST(SUM(client_quotations.quotation_net_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Clients Quotations'"),
            DB::raw("CONCAT(CAST(SUM(client_quotations.quotation_net_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Added Clients Quotations'")
        ])->groupBy('client_quotations.currency_id');
        return $query;
    }
}