<?php

namespace App\Traits;

trait WithoutAppends
{
    /**
     * Determines if the defaults appends fields will be present.
     *
     * @var bool
     */
    public static bool $withoutAppends = false;

    /**
     * Appends only the following fields.
     *
     * @var array
     */
    public static array $onlyThisFields = [];
  
    protected function getArrayableAppends()
    {
        if (self::$withoutAppends) {
            return self::$onlyThisFields;
        }

        return parent::getArrayableAppends();
    }
}