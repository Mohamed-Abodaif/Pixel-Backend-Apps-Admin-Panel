<?php

namespace App\Traits;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait CalculateBoxes
{
    /*
     * Calculate Total $context
     *
     * */
    public static function calculateBoxes(string $context, string $period = null, string | DateTime $from = null, string | DateTime $to = null, array $filters = [])
    {
        $data = null;
        $query = (match($period) {
            'all-time' => self::calculateBoxesByAllTimePeriod($context, $filters),
            'day' => self::calculateBoxesByDayPeriod($context, $from, $filters),
            'month' => self::calculateBoxesByMonthPeriod($context, $from, $filters),
            'quarter' => self::calculateBoxesByQuarterPeriod($context, $from, $filters),
            'year' => self::calculateBoxesByYearPeriod($context, $from, $filters),
            'range' => self::calculateBoxesByRangePeriod($context, $from, $to, $filters),
            default=> self::calculateBoxesByAllTimePeriod($context, $filters),
        });
        if (!is_null($query)) {
            return $data = $query->get();
            //$data = ($data && sizeof($data) === 1) ? $data->first() : $data;
        }
        return [];

    }

    private static function calculateBoxesByDayPeriod($context, $from, array $filters = [])
    {
        $from = Carbon::parseOrNow($from)->startOfDay();
        $to = Carbon::make($from)->endOfDay();
        return self::generateSqlForBoxes($context, $from, $to, $filters);
    }

    private static function calculateBoxesByMonthPeriod($context, $from, array $filters = [])
    {
        $from = Carbon::parseOrNow($from)->startOfMonth();
        $to = Carbon::make($from)->endOfMonth();
        return self::generateSqlForBoxes($context, $from, $to, $filters);
    }
    private static function calculateBoxesByQuarterPeriod($context, $from, array $filters = [])
    {
        $from = Carbon::parseOrNow($from)->startOfQuarter();
        $to = Carbon::make($from)->endOfQuarter();
        return self::generateSqlForBoxes($context, $from, $to, $filters);
    }
    private static function calculateBoxesByYearPeriod($context, $from, array $filters = [])
    {
        $from = Carbon::parseOrNow($from)->startOfYear();
        $to = Carbon::make($from)->endOfYear();
        return self::generateSqlForBoxes($context, $from, $to, $filters);
    }

    private static function calculateBoxesByRangePeriod($context, $from, $to, array $filters = [])
    {
        $from = Carbon::parseOrNow($from)->startOfDay();
        $to = Carbon::parseOrNow($to)->endOfDay();
        return self::generateSqlForBoxes($context, $from, $to, $filters);
    }

    private static function calculateBoxesByAllTimePeriod($context, array $filters = [])
    {
        $context_view = Str::snakeToTitle($context);

        switch (strtolower($context)) {
            case 'employees':
                $query = self::select([
                    DB::raw("COUNT(*) as 'Total $context_view'"),
                    DB::raw("COUNT(*) as 'Total Added $context_view'"),
                    DB::raw("SUM(if(status = 1,1,0)) as 'Total Active $context_view'"),
                    DB::raw("sum(if(status = 2, 1, 0))  as 'Total Inactive $context_view'"),
                ])->where('user_type', 'employee');
                break;
            case 'signup_requests':
                $query = self::select([
                    DB::raw("COUNT(*) as 'Total $context_view'"),
                    DB::raw("COUNT(*) as 'Total Added $context_view'"),
                    DB::raw("SUM(if(status = 0,1,0)) as 'Total Pending $context_view'"),
                    DB::raw("sum(if(status = 3, 1, 0))  as 'Total Blocked $context_view'"),
                ])->where('user_type', 'signup');
                break;
         //   case 'vendors':
            case 'clients':
                $query = self::select([
                    DB::raw("COUNT(*) as 'Total $context_view'"),
                    DB::raw("COUNT(*) as 'Total Added $context_view'"),
                    DB::raw("SUM(if(status = 1,1,0)) as 'Total Active $context_view'"),
                    DB::raw("SUM(if(status = 0,1,0)) as 'Total Inactive $context_view'"),
                ]);
                break;
            case 'expenses':
                // $main_currency = Currency::where('is_main', 1)->first() ?? Currency::where('status', 1)->first();
                // $query = self::where('user_id', auth()->user()->id)->select([
                //     DB::raw("COUNT(*) as 'Total $context_view'"),
                //     DB::raw("COUNT(*) as 'Total Added $context_view'"),
                //     DB::raw("SUM(CASE WHEN (status = 'Pending' ) THEN 1 ELSE 0 END) as 'Total Pending $context_view'"),
                //     DB::raw("SUM(CASE WHEN (status = 'Approved' ) THEN 1 ELSE 0 END) as 'Total Approved $context_view'"),
                //     DB::raw("SUM(CASE WHEN (status = 'Rejected' ) THEN 1 ELSE 0 END) as 'Total Rejected $context_view'"),
                //     //amounts
                //     DB::raw("SUM(CASE WHEN (status = 'Pending' ) THEN amount ELSE 0 END) as 'Total Pending $context_view ($main_currency->code)'"),
                //     DB::raw("SUM(CASE WHEN (status = 'Approved' ) THEN amount ELSE 0 END) as 'Total Approved $context_view ($main_currency->code)'"),
                //     DB::raw("SUM(CASE WHEN (status = 'Rejected' ) THEN amount ELSE 0 END) as 'Total Rejected $context_view ($main_currency->code)'"),
                // ]);
                    $query = self::join('currencies', 'expenses.currency_id', '=', 'currencies.id')
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
                break;
            case 'bonuses':
                $query = self::select([
                    DB::raw("COUNT(*) as 'Total $context_view'"),
                    DB::raw("COUNT(*) as 'Total Added $context_view'"),
                ])->where('user_id', auth()->user()->id);
                break;
            case 'custodies':
                $query = self::select([
                    DB::raw("COUNT(*) as 'Total $context_view'"),
                    DB::raw("COUNT(*) as 'Total Added $context_view'"),
                ])->where('user_id', auth()->user()->id);
                break;
            case 'expenses_currencies':
                $query = self::join('currencies', 'expenses.currency_id', '=', 'currencies.id')
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
                break;
            case 'clients_quotations':
                $query = self::join('currencies', 'client_quotations.currency_id', '=', 'currencies.id')
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
                break;
                case 'clients_orders':
                    $query = self::join('currencies', 'client_orders.currency_id', '=', 'currencies.id')
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
                    break;
            default:
                $query = self::select([
                    DB::raw("COUNT(*) as 'Total $context_view'"),
                    DB::raw("COUNT(*) as 'Total Added $context_view'"),
                ]);
                break;
        }

        return self::generateFilters($query, $filters);
    }

    private static function generateSqlForBoxes($context, $from, $to, array $filters = [])
    {
        $context_view = Str::snakeToTitle($context);
        switch (strtolower($context)) {
            case 'employees':
                $query = self::select([
                    DB::raw("SUM(CASE WHEN (created_at <= '$to') THEN 1 ELSE 0 END) as 'Total $context'"),
                    DB::raw("SUM(CASE WHEN (created_at >= '$from' AND created_at <= '$to') THEN 1 ELSE 0 END) as 'Total Added $context'"),
                    DB::raw("SUM(CASE WHEN (created_at >= '$from' AND created_at <= '$to' AND status=1) THEN 1 ELSE 0 END) as 'Total Active $context_view'"),
                    DB::raw("SUM(CASE WHEN (created_at >= '$from' AND created_at <= '$to' AND status=0) THEN 1 ELSE 0 END) as 'Total Inactive $context_view'"),
                ])->where('user_type', 'employee');
                break;
            case 'users':
                $query = self::select([
                    DB::raw("SUM(if(created_at <= '$to',1,0)) as 'Total $context_view'"),
                    DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to', 1, 0)) as 'Total Added $context_view'"),
                ])->where('user_type', 'signup');
                break;
            case 'signup_requests':
                $query = self::select([
                    DB::raw("SUM(if(created_at <= '$to', 1, 0)) as 'Total Signup Requests'"),
                    DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to', 1, 0)) as 'Total Added Signup Requests'"),
                    DB::raw("SUM(CASE WHEN (created_at >= '$from' AND created_at <= '$to' AND status=0) THEN 1 ELSE 0 END) as 'Total Pending $context_view'"),
                    DB::raw("SUM(CASE WHEN (created_at >= '$from' AND created_at <= '$to' AND status=3) THEN 1 ELSE 0 END) as 'Total Blocked $context_view'"),
                ])->where('user_type', 'signup');
                break;
                #TODO
            // case 'clients':
            //     $query = self::select([
            //         DB::raw("COUNT(if(created_at <= '$to', 1, 0)) as 'Total $context_view'"),
            //         DB::raw("COUNT(*) as 'Total Added $context_view'"),
            //         DB::raw("SUM(if(status = 1,1,0)) as 'Total Active $context_view'"),
            //         DB::raw("SUM(if(status = 0,1,0)) as 'Total Inactive $context_view'"),
            //     ]);
            //     break;
            case 'vendors':
                $query = self::select([
                    DB::raw("SUM(if(created_at <= '$to', 1, 0)) as 'Total $context_view'"),
                    DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to', 1, 0)) as 'Total Added $context_view'"),
                    DB::raw("SUM(CASE WHEN (created_at >= '$from' AND created_at <= '$to' AND status=1) THEN 1 ELSE 0 END) as 'Total Active $context_view'"),
                    DB::raw("SUM(CASE WHEN (created_at >= '$from' AND created_at <= '$to' AND status=0) THEN 1 ELSE 0 END) as 'Total Inactive $context_view'"),
                ]);
                break;
            case 'expenses':
                // $main_currency = Currency::where('is_main', 1)->first() ?? Currency::where('status', 1)->first();
                // $query = self::select([
                //     DB::raw("SUM(if(created_at <= '$to', 1, 0)) as 'Total $context_view'"),
                //     DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to', 1, 0)) as 'Total Added $context_view'"),
                //     DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to' AND status='Pending' , 1, 0 )) as 'Total Pending $context_view'"),
                //     DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to' AND status='Approved', 1, 0 )) as 'Total Approved $context_view'"),
                //     DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to'  AND status='Rejected', 1, 0)) as 'Total Rejected $context_view'"),
                //     //amounts
                //     DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to' AND status='Pending' , amount, 0 )) as 'Total Pending $context_view ($main_currency->code)'"),
                //     DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to' AND status='Approved' , amount, 0 )) as 'Total Approved $context_view ($main_currency->code)'"),
                //     DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to' AND status='Rejected' , amount, 0 )) as 'Total Rejected $context_view ($main_currency->code)'"),
                // ])->where('user_id', auth()->user()->id);

                $query = self::join('currencies', 'expenses.currency_id', '=', 'currencies.id')
                ->where('user_id', auth()->user()->id)
                    ->select([
                        'currencies.name',
                        'currencies.code',
                        'currencies.symbol',
                        DB::raw("COUNT(expenses.id) as 'Total Records'"),
                        DB::raw("COUNT(expenses.id) as 'Total Added Records'"),
                        //amounts
                        DB::raw("CONCAT(CAST(SUM(expenses.amount) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Expenses'"),
                        DB::raw("CONCAT(CAST(SUM(expenses.amount) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Added Expenses'")
                    ])
                    ->where('expenses.created_at','>=', $from)
                    ->where('expenses.created_at','<=', $to)
                    ->groupBy('expenses.currency_id');
                break;

            case 'custodies':
                $query = self::select([
                    DB::raw("SUM(if(created_at <= '$to', 1, 0)) as 'Total $context_view'"),
                    DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to', 1, 0)) as 'Total Added $context_view'"),
                ])->where('user_id', auth()->user()->id);
                break;
            case 'bonuses':
                $query = self::select([
                    DB::raw("SUM(if(created_at <= '$to', 1, 0)) as 'Total $context_view'"),
                    DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to', 1, 0)) as 'Total Added $context_view'"),
                ])->where('user_id', auth()->user()->id);
                break;
            case 'expenses_currencies':
                $query = self::join('currencies', 'expenses.currency_id', '=', 'currencies.id')
                    ->select([
                        'currencies.name',
                        'currencies.code',
                        'currencies.symbol',
                        DB::raw("COUNT(expenses.id) as 'Total Records'"),
                        DB::raw("COUNT(expenses.id) as 'Total Added Records'"),
                        //amounts
                        DB::raw("CONCAT(CAST(SUM(expenses.amount) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Expenses'"),
                        DB::raw("CONCAT(CAST(SUM(expenses.amount) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Added Expenses'")
                    ])
                    ->where('expenses.created_at','>=', $from)
                    ->where('expenses.created_at','<=', $to)
                    ->groupBy('expenses.currency_id');
                break;
            case 'clients_quotations':
                $query = self::join('currencies', 'client_quotations.currency_id', '=', 'currencies.id')
                    ->select([
                        'currencies.name',
                        'currencies.code',
                        'currencies.symbol',
                        DB::raw("COUNT(client_quotations.id) as 'Total Records'"),
                        DB::raw("COUNT(client_quotations.id) as 'Total Added Records'"),
                        //amounts
                        DB::raw("CONCAT(CAST(SUM(client_quotations.quotation_net_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Clients Quotations'"),
                        DB::raw("CONCAT(CAST(SUM(client_quotations.quotation_net_value) as DECIMAL(18, 2)), ' ', currencies.symbol) as 'Total Added Clients Quotations'")
                    ])
                    ->where('client_quotations.created_at','>=', $from)
                    ->where('client_quotations.created_at','<=', $to)
                    ->groupBy('client_quotations.currency_id');
                break;

             case 'clients_orders':
                $query = self::join('currencies', 'client_orders.currency_id', '=', 'currencies.id')
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

                    ])
                    ->where('client_orders.created_at','>=', $from)
                    ->where('client_orders.created_at','<=', $to)
                    ->groupBy('client_orders.currency_id');
                break;
            default:
                $query = self::select([
                    DB::raw("SUM(if(created_at <= '$to', 1, 0)) as 'Total $context_view'"),
                    DB::raw("SUM(if(created_at >= '$from' AND created_at <= '$to', 1, 0)) as 'Total Added $context_view'"),
                ]);
                break;
        }

        return self::generateFilters($query, $filters);

    }

    private static function generateFilters($query, array $filters) {
        if ($query && sizeof($filters) > 0) {
            foreach ($filters as $fun => $filter) {
                foreach ($filter as $funArgs) {
                    call_user_func(array($query, "$fun"), ...$funArgs);
                }
            }
        }
        return $query;
    }
}
