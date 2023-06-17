<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterBuilder;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Exporter;
use App\Exceptions\FileTypeNotSupported;
use Exception;
use Illuminate\Http\Request;

abstract class ExporterBuilder
{
    use BuilderValidationMethods;

    abstract protected function getExportTypesMap()  :array;

    /**
     * @param Request|array $request
     * @throws FileTypeNotSupported
     * @throws Exception
     */
    public function __construct(Request | array $request)
    {
        $this->validateRequest($request)->validateTypeValue();
    }

    public function build() : Exporter
    {
        $class = $this->getExportTypesMap()[$this->data["type"]];
        return new $class;
    }

}
