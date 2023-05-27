<?php

use Illuminate\Support\Str;

Str::macro('snakeToTitle', function ($value) {
    return ucwords(str_replace('_', ' ', $value));
});

