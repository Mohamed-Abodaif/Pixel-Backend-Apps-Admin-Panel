<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait CalculateTotal
{
    public static function calculateTotal($period = null, $from = null, $to = null, $queries = [])
    {    
        $data = null;
        $query =  (match ($period) {
            'all-time' => self::calculateTotalByAllTimePeriod($queries),
            'day' => self::calculateTotalByDayPeriod($queries, $from),
            'month' => self::calculateTotalByMonthPeriod($queries, $from),
            'quarter' => self::calculateTotalByQuarterPeriod($queries, $from),
            'year' => self::calculateTotalByYearPeriod($queries, $from),
            'range' => self::calculateTotalByRangePeriod($queries, $from, $to),
            default => self::calculateTotalByAllTimePeriod($queries),
        });
        if (!is_null($query)) {
            $data = $query->where('deleted_at', NULL)->get()->first();
            $data = !is_null($data) ? $data->setHidden($data->getArrayableAppends()) : [];
        }
        
        return $data;
    }

    private static function calculateTotalByAllTimePeriod($queries)
    {
        $q = null;
        foreach ($queries as $query) {
            $condition = $query['condition'];
            $message = $query['message'];
            if(in_array($message,['assets','company operations','client PO','marketing','client visits & preorders','purchase for inventory']))
            {
                $user_id=auth()->user()->id;
                $sql = ($condition == "*" || $condition == "") ? "COUNT(*) AS '$message'" : "SUM($condition && status='Approved' && user_id=$user_id) AS '$message'";
            }
            else
            {
                $sql = ($condition == "*" || $condition == "") ? "COUNT(*) AS '$message'" : "SUM($condition) AS '$message'";
            }
            if (is_null($q)) {
                $q = self::select(DB::raw($sql));
                continue;
            }
            $q = $q->addSelect(DB::raw($sql));
        }
        return $q;
    }

    private static function calculateTotalByDayPeriod($queries, $from)
    {
        
        $from = Carbon::parseOrNow($from)->startOfDay();
        $to = Carbon::make($from)->endOfDay();
        return self::generateSqlForTotal($queries, $from, $to);
    }

    private static function calculateTotalByMonthPeriod($queries, $from)
    {
        $from = Carbon::parseOrNow($from)->startOfMonth();
        $to = Carbon::make($from)->endOfMonth();
        return self::generateSqlForTotal($queries, $from, $to);
    }

    private static function calculateTotalByQuarterPeriod($queries, $from)
    {
        $from = Carbon::parseOrNow($from)->startOfQuarter();
        $to = Carbon::make($from)->endOfQuarter();
        return self::generateSqlForTotal($queries, $from, $to);
    }


    private static function calculateTotalByYearPeriod($queries, $from)
    {
        $from = Carbon::parseOrNow($from)->startOfYear();
        $to = Carbon::make($from)->endOfYear();
        return self::generateSqlForTotal($queries, $from, $to);
    }

    private static function calculateTotalByRangePeriod($queries, $from, $to)
    {
        $from = Carbon::parseOrNow($from)->startOfDay();
        $to = Carbon::parseOrNow($to)->endOfDay();
        return self::generateSqlForTotal($queries, $from, $to);
    }

    private static function generateSqlForTotal($queries, $from, $to)
    {
        $q = null;
        foreach ($queries as $query) {
            $condition = $query['condition'];
            $message = $query['message'];
            if(in_array($message,['assets','company operations','client PO','marketing','client visits & preorders','purchase for inventory']))
            {
                $sql = ($condition == "*" || $condition == "") ? "SUM(created_at <= '$to') AS '$message'" : "SUM($condition AND status='Approved' AND created_at BETWEEN '$from' AND '$to') AS '$message'";

            }
            else
            {
            
                $sql = ($condition == "*" || $condition == "") ? "SUM(created_at <= '$to') AS '$message'" : "SUM($condition AND created_at BETWEEN '$from' AND '$to') AS '$message'";
            }
            if (is_null($q)) {
            $q = self::select(DB::raw($sql));
            continue;
            }
            $q = $q->addSelect(DB::raw($sql));
        }
        return $q;
    }
}
