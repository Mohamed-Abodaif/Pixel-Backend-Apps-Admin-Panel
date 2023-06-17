<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\EXCELExporter\Responders;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders\StreamingResponder;
use Illuminate\Http\JsonResponse;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EXCELStreamingResponder extends StreamingResponder
{

    protected FastExcel $excel;

    /**
     * @param FastExcel $excel
     * @return $this
     */
    public function setExcel(FastExcel $excel): self
    {
        $this->excel = $excel;
        return $this;
    }

    /**
     * @return BinaryFileResponse|StreamedResponse|JsonResponse|string
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function respond():BinaryFileResponse | StreamedResponse | JsonResponse| string
    {
        return $this->excel->download($this->FileFullName);
    }
}
