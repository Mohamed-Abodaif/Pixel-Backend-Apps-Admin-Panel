<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\PDFExporter\Responders;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders\StreamingResponder;
use Illuminate\Http\JsonResponse;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PDFStreamingResponder extends StreamingResponder
{
    protected MPDF $mpdf;

    /**
     * @param Mpdf $MPDF
     * @return $this
     */
    public function setMPDF(Mpdf $MPDF): self
    {
        $this->mpdf = $MPDF;
        return $this;
    }

    /**
     * @return BinaryFileResponse|StreamedResponse|JsonResponse|string
     * @throws MpdfException
     */
    public function respond():BinaryFileResponse | StreamedResponse | JsonResponse| string
    {
        return $this->mpdf->Output($this->FileFullName, 'D');
    }
}
