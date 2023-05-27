<?php

use Illuminate\Database\Schema\Blueprint;


Blueprint::macro('status', function ($default = 1) {
    $this->tinyInteger('status')->default($default);
});
Blueprint::macro('standardTime', function () {
    $this->timestamp('created_at')->useCurrent();
    $this->timestamp('updated_at')->nullable();
    $this->softDeletes();
});
