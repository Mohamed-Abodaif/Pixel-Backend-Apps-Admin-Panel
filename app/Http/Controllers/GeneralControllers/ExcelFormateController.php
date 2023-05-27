<?php

namespace App\Http\Controllers\GeneralControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response as FacadeResponse;

class ExcelFormateController extends Controller
{

    public function downloadExcelFromate()
    {
        $path = Storage::disk('local')->path('system_config.xlsx');
        return FacadeResponse::download($path);
    }
}
