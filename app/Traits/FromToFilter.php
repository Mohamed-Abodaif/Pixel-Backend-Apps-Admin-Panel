<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait FromToFilter
{
    public function scopeFromDate(Builder $query, $date): Builder
    {
        $time = Carbon::parseOrDefault($date);
        return $query->where('created_at', '>=', $time->startOfDay());
    }

    public function scopeToDate(Builder $query, $date): Builder
    {
        $time = Carbon::parseOrDefault($date);
        return $query->where('created_at', '<=', $time->endOfDay());
    }
}
