<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\ImporterBuilder;

use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\Importer;
use App\Exceptions\JsonException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

abstract class ImporterBuilder
{

    use ValidationMethods;
    protected array $data = [];

    abstract protected function getImporterTypesMap()  :array;

    /**
     * @return Importer
     * @throws JsonException
     */
    protected function getImporterType() : Importer
    {
//        $importerClass = $this->getImporterTypesMap()[$this->getNeededImporterExtension()];
//        return new $importerClass();
    }

    protected function setImporterProps(Importer $importer) : Importer
    {
        $importer->setUploadedFile($this->file);
        return $importer;
    }

    /**
     * @return Importer
     * @throws JsonException
     */
    protected function buildImporter() : Importer
    {
        return $this->setImporterProps(
                    $this->getImporterType()
                );
    }


    /**
     * @param Request|array $request
     * @return Importer|JsonResponse
     */
    public function build(Request | array $request) : Importer | JsonResponse
    {
        try {
            return $this->validateOperation($request)->buildImporter();
        }catch(Exception $e)
        {
            return Response::error([$e->getMessage()]);
        }
    }

}
