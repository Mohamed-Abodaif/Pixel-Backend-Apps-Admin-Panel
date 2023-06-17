<?php
namespace App\Statistics\ClientsOrdersStatistics;

use App\Statistics\Interfaces\BoxesInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientsOrdersBoxes implements BoxesInterface{
    public static function query($context_view){
        $query = DB::table('currencies')->jjoin('currencies', 'client_orders.currency_id', '=', 'currencies.id')
        ->select([
            'currencies.name',
            'currencies.code',
            'currencies.symbol',
            DB::raw("COUNT(client_orders.currency_id) as 'Total Records'"),
            DB::raw("COUNT(client_orders.currency_id) as 'Total Added Records'"),
            //amounts
            DB::raw("CONCAT(CAST(SUM(client_orders.po_total_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total POs Value'"),
            DB::raw("CONCAT(CAST(SUM(client_orders.po_sales_taxes_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total POs Sales Taxes Value'"),
            DB::raw("CONCAT(CAST(SUM(client_orders.po_add_and_discount_taxes_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total POs Add/Disc Value'"),
            DB::raw("CONCAT(CAST(SUM(client_orders.po_net_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total POs Net Value'"),

            DB::raw("CONCAT(CAST(SUM(client_orders.po_total_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Added POs Value'"),
            DB::raw("CONCAT(CAST(SUM(client_orders.po_sales_taxes_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Added POs Sales Taxes Value'"),
            DB::raw("CONCAT(CAST(SUM(client_orders.po_add_and_discount_taxes_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Added POs Add/Disc Value'"),
            DB::raw("CONCAT(CAST(SUM(client_orders.po_net_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Added POs Net Value'"),

        ])->groupBy('client_orders.currency_id');
        return $query;
    }
}