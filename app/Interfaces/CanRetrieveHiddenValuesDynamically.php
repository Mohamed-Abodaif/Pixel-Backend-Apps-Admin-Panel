<?php

namespace App\Interfaces;

interface CanRetrieveHiddenValuesDynamically
{
    public function retrieveAllHiddenAttributes() ;
    public function retrieveHiddenAttribute(string $hiddenAttributeToRetrieve) ;
    public function retrieveArrayOfHiddenAttributes(array $hiddenAttributesToRetrieve) ;
}
