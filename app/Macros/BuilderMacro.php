<?php

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

Builder::macro('datesFiltering', function () {
    $period_type = request()->filter['period_type'] ?? null;
    $from_date = request()->filter['from_date'] ?? null;
    $to_date = request()->filter['to_date'] ?? null;
    switch ($period_type) {
        case 'day':
            $from = Carbon::parseOrNow($from_date)->startOfDay();
            $to = Carbon::make($from_date)->endOfDay();
            break;
        case 'month':
            $from = Carbon::parseOrNow($from_date)->startOfMonth();
            $to = Carbon::make($from_date)->endOfMonth();
            break;
        case 'quarter':
            $from = Carbon::parseOrNow($from_date)->startOfQuarter();
            $to = Carbon::make($from_date)->endOfQuarter();
            break;
        case 'year':
            $from = Carbon::parseOrNow($from_date)->startOfYear();
            $to = Carbon::make($from_date)->endOfYear();
            break;
        case 'range':
            $from = Carbon::parseOrNow($from_date)->startOfDay();
            $to = Carbon::parseOrNow($to_date)->endOfDay();
            break;
        default:
            break;
    }
    if (isset($from) && isset($to)) {
       return $this->where('created_at', '>=', $from)->where('created_at', '<=', $to);
    }
    return $this;
});


Builder::macro('customOrdering', function ($sortColumn = null, $sort = null) {
    return $this->orderBy($sortColumn ?? request()->sortColumn ?? 'id', $sort ?? request()->sort ?? 'desc');
});
