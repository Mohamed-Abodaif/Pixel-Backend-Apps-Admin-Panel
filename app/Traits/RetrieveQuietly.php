<?php
namespace App\Traits;

trait RetrieveQuietly
{
    /**
     * Save model without triggering observers on model
     */
    public function retrieveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->get($options);
        });
    }
}