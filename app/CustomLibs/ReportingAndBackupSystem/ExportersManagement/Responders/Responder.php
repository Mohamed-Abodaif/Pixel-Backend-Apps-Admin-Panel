<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Responder
{
    abstract public function respond() :BinaryFileResponse | StreamedResponse | JsonResponse | string;

}
