<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait UserStatus
{

    protected function status(): Attribute
    {
        return new Attribute(
            get:function (int $value) {
                //logic
                $label = 'Pending';
                if ($value == 1) {
                    $label = 'Active';
                } elseif ($value == 2) {
                    $label = 'Inactive';
                } elseif ($value == 3) {
                    $label = 'Blocked';
                }
                return ["label" => $label, "value" => $value];
            }
        );
    }
}
