<?php

namespace App\CustomLibs\ExportersManagement\Interfaces;

interface NeedExcelStyle
{
    public function setHeaderStyle( $style);
    public function setRowStyle( $style);
}
