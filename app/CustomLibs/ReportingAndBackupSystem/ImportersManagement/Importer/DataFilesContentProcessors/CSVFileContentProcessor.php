<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Importer\DataFilesContentProcessors;

use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;

class CSVFileContentProcessor extends DataFileContentProcessor
{
    /**
     * @return array
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    public function getData(): array
    {
        return (new FastExcel)->import( $this->filePathToProcess )->toArray();
    }
}
