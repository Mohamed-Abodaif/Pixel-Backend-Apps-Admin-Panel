<?php

namespace App\Interfaces;

interface ExcelInterface
{
    public function importFile($request);
    public function exportFile($request);
}
