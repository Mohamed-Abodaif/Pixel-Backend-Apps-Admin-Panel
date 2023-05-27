<?php

namespace App\CustomLibs\ExportersManagement\Interfaces;

use App\CustomLibs\ExportersManagement\ExporterStringValueProcessor\ExporterStringValueProcessor;

interface NeedToProcessStringValues
{
    public function initStringValueProcessor() : self;

    public function getCurrentStringProcessor() : ExporterStringValueProcessor;
}
