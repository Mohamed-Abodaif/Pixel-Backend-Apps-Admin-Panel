<?php

namespace App\CustomLibs\ExportersManagement\ExporterTypes;

use App\CustomLibs\ExportersManagement\Exporter\PresentationDataExporter;
use App\CustomLibs\ExportersManagement\Interfaces\NeedExcelStyle;
use Exception;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class ExcelExporter extends PresentationDataExporter implements NeedExcelStyle
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
    protected function initFastExcel() : self
    {
        $this->excel = new FastExcel();
        return $this;
    }
    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    protected function setDefaultHeaderStyle() : self
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
    public function setHeaderStyle( $style) : self
    {
        if (!$style instanceof Style) { throw new Exception("The Given Style Is Not valid !"); }
        $this->excel->headerStyle($style);
        return $this;
    }

    /**
     * @return $this
     * @throws InvalidArgumentException
     */
    protected function setDefaultRowStyle() : self
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
    public function setRowStyle( $style)  :self
    {
        if (!$style instanceof Style) { throw new Exception("The Given Style Is Not valid !"); }
        $this->excel->rowsStyle($style);
        return $this;
    }

    /**
     * @return StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function exportingFun()
    //: StreamedResponse
    {
        return $this->excel->data($this->DataToExport)->download($this->fileName . '.xlsx');
    }
}
