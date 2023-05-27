<?php

namespace App\Files\Excel;

use App\Interfaces\ExcelInterface;
use App\Models\WorkSector\CompanyModule\Company;
use Rap2hpoutre\FastExcel\FastExcel as Excel;

class FastExcel implements ExcelInterface
{
    public function importFile($request)
    {
        $file = $request->file('file');

        $collection = (new Excel)->import($file);
        // return Company::create($collection);
    }

    public function exportFile($request)
    {
        return (new Excel(Company::get()))->download('file.xlsx');
    }
}
