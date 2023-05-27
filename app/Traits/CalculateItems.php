<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait CalculateItems
{

    private static $opts = [
        'year' => ['sub' => 4, 'format' => 'YYYY'],
        'quarter' => ['sub' => 16, 'format' => 'YYYY-Qo'],
        'month' => ['sub' => 12, 'format' => 'YYYY-MMM'],
        'day' => ['sub' => 14, 'format' => 'MMM-D'],
        'all-time' => ['sub' => 14, 'format' => 'MMM-D']
    ];

    public static function calculateItems($period = null, $from = null, $to = null, $opts = [])
    {
        $opts = array_merge(self::$opts, $opts);
        $data= (match ($period) {
            'all-time' => self::calculateItemsByAllTimePeriod($opts['all-time']),
            'day' => self::calculateItemsByDayPeriod($from, $opts['day']),
            'month' => self::calculateItemsByMonthPeriod($from, $opts['month']),
            'quarter' => self::calculateItemsByQuarterPeriod($from, $opts['quarter']),
            'year' => self::calculateItemsByYearPeriod($from, $opts['year']),
            'range' => self::calculateItemsByRangePeriod($from, $to, $opts),
            default => self::calculateItemsByAllTimePeriod($opts['all-time']),
        })->where('deleted_at', NULL)->get()->first()->setHidden(['role', 'roles']);

        // $data = $data->setHidden($data->getArrayableAppends());
        
        return $data; 
    }

    private static function calculateItemsByAllTimePeriod($opts)
    {
        $now = Carbon::now();
        return static::calculateItemsByDayPeriod($now, $opts);
    }

    private static function calculateItemsByDayPeriod($from, $opts)
    {
        $format = $opts['format'];
        $end = Carbon::parseOrNow($from)->endOfDay();
        $start = Carbon::make($end)->subDays($opts['sub']);
        $intervals = Carbon::make($start)->toPeriod($end, 1, 'days');
        $intervals = $intervals->map(function (Carbon $date) use ($format) {
            $from = Carbon::make($date)->startOfDay();
            $to = Carbon::make($date)->endOfDay();
            $formated = $date->isoformat($format);
            return DB::raw("SUM(created_at BETWEEN '$from' AND '$to') as '$formated'");
        });

        return self::generateSqlForItems($intervals);
    }

    private static function calculateItemsByMonthPeriod($from, $opts)
    {
        $format = $opts['format'];
        $end = Carbon::parseOrNow($from)->endOfMonth();
        $start = Carbon::make($end)->subMonths($opts['sub']);
        $intervals = Carbon::make($start)->toPeriod($end, 1, 'months');
        $intervals = $intervals->map(function (Carbon $date) use ($format) {
            $from = Carbon::make($date)->startOfMonth();
            $to = Carbon::make($date)->endOfMonth();
            $formated = $date->isoformat($format);

            return DB::raw("SUM(created_at BETWEEN '$from' AND '$to') as '$formated'");
        });
        return self::generateSqlForItems($intervals);
    }

    private static function calculateItemsByQuarterPeriod($from, $opts)
    {
        $format = $opts['format'];
        $end = Carbon::parseOrNow($from)->endOfQuarter();
        $start = Carbon::make($end)->subQuarters($opts['sub']);
        $intervals = Carbon::make($start)->toPeriod($end, 1, 'quarters');
        $intervals = $intervals->map(function (Carbon $date) use ($format) {
            $from = Carbon::make($date)->startOfQuarter();
            $to = Carbon::make($date)->endOfQuarter();
            $formated = $date->isoformat($format);

            return DB::raw("SUM(created_at BETWEEN '$from' AND '$to') as '$formated'");
        });
        return self::generateSqlForItems($intervals);
    }


    private static function calculateItemsByYearPeriod($from, $opts)
    {
        $format = $opts['format'];
        $end = Carbon::parseOrNow($from)->endOfYear();
        $start = Carbon::make($end)->subYears($opts['sub']);
        $intervals = Carbon::make($start)->toPeriod($end, 1, 'years');
        $intervals = $intervals->map(function (Carbon $date) use ($format) {
            $from = Carbon::make($date)->startOfYear();
            $to = Carbon::make($date)->endOfYear();
            $formated = $date->isoformat($format);
            //TODO: maintain whitespace in '$formated ' do not delete it.
            //You will lose hours to debug it.
            return DB::raw("SUM(created_at BETWEEN '$from' AND '$to') as '$formated'");
        });
        return self::generateSqlForItems($intervals);
    }

    private static function calculateItemsByRangePeriod($from, $to, $opts)
    {
        $from = Carbon::parseOrNow($from);
        $to = Carbon::parseOrNow($to);
        $diff = $from->diffInDays($to);
        return match (true) {
            ($diff <= 31) => self::calculateItemsByDayPeriod($to, $opts['day']),
            ($diff > 31 and $diff <= 124) => self::calculateItemsByMonthPeriod($to, $opts['month']),
            ($diff > 124 and $diff <= 366) => self::calculateItemsByQuarterPeriod($to, $opts['quarter']),
            default => self::calculateItemsByYearPeriod($to, $opts['year'])
        };
    }

    private static function generateSqlForItems($intervals)
    {
        $queries = iterator_to_array($intervals);
        return self::select($queries);
    }
}
