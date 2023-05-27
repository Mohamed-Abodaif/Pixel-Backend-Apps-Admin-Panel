<?php

namespace App\Services\WorkSector\SystemConfigurationServices;

trait CustomisationHooksMethods
{

    //////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////
    //Methods For Customizing Some Operations If There Is Need To That
    protected function DefinitionRelationshipsHandler(): self
    {
        return $this;
    }
    protected function doBeforeOperation(): self
    {
        return $this;
    }
    protected function doAfterOperation(): self
    {
        return $this;
    }
    //////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////
}
