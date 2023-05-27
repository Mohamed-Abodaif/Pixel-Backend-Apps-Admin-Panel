<?php

namespace App\Http\Controllers\WorkSector\CompanyModule;

use App\Http\Controllers\Controller;
use App\Interfaces\ExcelInterface;
use Illuminate\Http\Request;

class CompanyFileController extends Controller
{
    protected $excel;
    function __construct(ExcelInterface $excel)
    {
        $this->excel = $excel;
    }
    public function importCompanies(Request $request)
    {
        return $file = $this->excel->importFile($request);
    }

    public function exportCompanies(Request $request)
    {
        return $file = $this->excel->exportFile($request);
    }
}
