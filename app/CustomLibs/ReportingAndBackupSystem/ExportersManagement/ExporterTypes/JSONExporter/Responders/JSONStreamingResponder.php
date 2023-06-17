<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\JSONExporter\Responders;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders\StreamingResponder;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JSONStreamingResponder extends StreamingResponder
{

    protected string $jsonContent;

    /**
     * @param string $jsonContent
     * @return $this
     */
    public function setJsonContent(string $jsonContent): self
    {
        $this->jsonContent = $jsonContent;
        return $this;
    }
    /**
     * @return BinaryFileResponse|StreamedResponse|JsonResponse|string
     */
    public function respond():BinaryFileResponse | StreamedResponse | JsonResponse| string
    {
        return response()->streamDownload(
                                function () { echo $this->jsonContent; } ,
                                $this->FileFullName ,
                                ['Content-Type' => 'application/json' ]
                            );
    }
}
