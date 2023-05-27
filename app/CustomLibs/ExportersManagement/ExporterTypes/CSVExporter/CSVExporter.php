<?php


namespace App\CustomLibs\ExportersManagement\ExporterTypes\CSVExporter;

use App\CustomLibs\ExportersManagement\Exporter\ImportingDataExporter;
use App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\DataArrayProcessor;
use App\CustomLibs\ExportersManagement\FinalDataArrayProcessors\ImportingDataArrayProcessors\ParentRelationshipsContainerArrayProcessor;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class CSVExporter extends ImportingDataExporter
{
    protected function getFinalDataArrayProcessor(): DataArrayProcessor
    {
        return new ParentRelationshipsContainerArrayProcessor();
    }

    /**
     * @return StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    protected function exportingFun()
    //: StreamedResponse
    {
        return (new FastExcel($this->DataToExport))->download($this->fileName . '.csv');
    }

}
