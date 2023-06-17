<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\EXCELExporter;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Exporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\ExportersMainTypes\PresentationDataExporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\EXCELExporter\Responders\EXCELStreamingResponder;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Interfaces\NeedExcelStyle;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders\StreamingResponder;
use Exception;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;

abstract class EXCELExporter extends PresentationDataExporter implements NeedExcelStyle
{

    protected FastExcel $excel;

    function __construct()
    {
        parent::__construct();
        $this->initFastExcel()->setDefaultHeaderStyle()->setDefaultRowStyle();
    }

    /**
     * @return $this
     */
    protected function initFastExcel(): self
    {
        $this->excel = new FastExcel();
        return $this;
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    protected function setDefaultHeaderStyle(): self
    {
        $style = (new StyleBuilder())->setFontSize(15)
            ->setFontColor("#FFFFFF")
            ->setBackgroundColor("84CC16")
            ->setCellAlignment('center')
            ->build();
        $this->excel->headerStyle($style);
        return $this;
    }

    /**
     * @param $style
     * @return $this
     * @throws Exception
     */
    public function setHeaderStyle($style): self
    {
        if (!$style instanceof Style) {
            throw new Exception("The Given Style Is Not valid !");
        }
        $this->excel->headerStyle($style);
        return $this;
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    protected function setDefaultRowStyle(): self
    {
        $style = (new StyleBuilder())->setCellAlignment('center')->build();
        $this->excel->rowsStyle($style);
        return $this;
    }

    /**
     * @param $style
     * @return $this
     * @throws Exception
     */
    public function setRowStyle($style): self
    {
        if (!$style instanceof Style) {
            throw new Exception("The Given Style Is Not valid !");
        }
        $this->excel->rowsStyle($style);
        return $this;
    }

    protected function getStreamingResponder(): StreamingResponder
    {
        return new EXCELStreamingResponder();
    }

    /**
     * @return Exporter
     */
    protected function setStreamingResponderResponseProps(): Exporter
    {
        $this->excel->data($this->DataToExport);
        /**  @var EXCELStreamingResponder $this- >responder */
        $this->responder->setExcel($this->excel);
        return $this;
    }

    protected function getDataFileExtension() : string
    {
        return "xlsx";
    }

    /**
     * @return string
     * Return Exported Data File Path
     *
     * @throws InvalidArgumentException
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     * @throws Exception
     */
    protected function setDataFileToExportedFilesProcessor(): string
    {
        $this->excel->data($this->DataToExport);
        return $this->filesProcessor->HandleTempFileToCopy(
            $this->excel->export($this->fileFullName),
            $this->fileFullName
        )->copyToTempPath();
    }
}
