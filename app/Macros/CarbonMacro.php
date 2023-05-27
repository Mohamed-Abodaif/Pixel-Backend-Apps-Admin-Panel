<?php

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

Carbon::macro('parseOrNow', function ($date = ""): Carbon {
    try {
        return $date ? Carbon::parse($date) : Carbon::now();
    } catch (InvalidFormatException) {
        return Carbon::now();
    }
});
