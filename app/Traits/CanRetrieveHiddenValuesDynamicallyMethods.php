<?php

namespace App\Traits;

trait CanRetrieveHiddenValuesDynamicallyMethods
{
    public function retrieveAllHiddenAttributes()
    {
        $this->hidden = [];
    }

    public function retrieveHiddenAttribute(string $hiddenAttributeToRetrieve)
    {
        if(array_key_exists($hiddenAttributeToRetrieve , $this->hidden))
        {
            unset($this->hidden[$hiddenAttributeToRetrieve]);
        }
    }

    public function retrieveArrayOfHiddenAttributes(array $hiddenAttributesToRetrieve)
    {
        foreach ($hiddenAttributesToRetrieve as $attribute)
        {
            $this->retrieveHiddenAttribute($attribute);
        }
    }
}
