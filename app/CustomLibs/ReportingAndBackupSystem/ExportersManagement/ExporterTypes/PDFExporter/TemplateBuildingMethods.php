<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\PDFExporter;

use Mpdf\MpdfException;

trait TemplateBuildingMethods
{
    protected array $ViewDataArray = [];

    protected function setViewDataArray() : void
    {
        $this->ViewDataArray["title"] = $this->mpdf->title;
        $this->ViewDataArray["Data"] = $this->DataToExport;

        if($this->TemplateBladeName == PDFExporter::TABLE_TEMPLATE)
        {
            $this->ViewDataArray["DataKeys"] = array_keys($this->DataToExport[0]);
        }
    }

    /**
     * @return PDFExporter|TemplateBuildingMethods
     * @throws MpdfException
     */
    protected function buildTemplate() : self
    {
        $this->setViewDataArray();
        $viewRenderedString = view( $this->TemplateBladeName , $this->ViewDataArray )->render();
        $this->mpdf->writeHTML($viewRenderedString);
        return $this;
    }

}
