<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders;

abstract class StreamingResponder extends Responder
{

    protected  array $DataToExport = [];
    protected string $FileFullName ;

    /**
     * @param string $FileFullName
     * @return $this
     */
    public function setFileFullName(string $FileFullName): self
    {
        $this->FileFullName = $FileFullName;
        return $this;
    }

    /**
     * @param array $DataToExport
     * @return $this
     */
    public function setDataToExport(array $DataToExport): self
    {
        $this->DataToExport = $DataToExport;
        return $this;
    }
}
