<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces;

interface NeedExcelStyle
{
    public function setHeaderStyle( $style);
    public function setRowStyle( $style);
}
