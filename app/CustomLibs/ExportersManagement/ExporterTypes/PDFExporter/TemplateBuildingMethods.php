<?php

namespace App\CustomLibs\ExportersManagement\ExporterTypes\PDFExporter;

use Mpdf\MpdfException;

trait TemplateBuildingMethods
{
    /**
     * @return PDFExporter|TemplateBuildingMethods
     * @throws MpdfException
     */
    protected function buildTemplate() : self
    {
        $lazy = $this->DataToExport;
        foreach ($lazy as $k => $v) {
            $this->mpdf->writeHTML('<div class="container">');
            $this->mpdf->writeHTML('<h1>' . $this->mpdf->title . '</h1>');
            $this->mpdf->writeHTML('<table>');
            foreach ($v as $i => $data) {
                $items = call_user_func($cb, $data);
                if ($i === 0) {
                    $keys = array_keys($items);
                    $this->mpdf->writeHTML('<thead>');
                    $this->mpdf->writeHTML('<tr>');
                    foreach ($keys as $h) {
                        $this->mpdf->writeHTML("<th>" . $h . "</th>");
                    }
                    $this->mpdf->writeHTML('</tr>');
                    $this->mpdf->writeHTML('</thead>');
                    $this->mpdf->writeHTML('<tbody>');
                }
                $values = array_values($items);
                $this->mpdf->writeHTML("<tr>");
                foreach ($values as $value) {
                    $this->mpdf->writeHTML("<td>" . $value . "</td>");
                }
                $this->mpdf->writeHTML("</tr>");
            }

            $this->mpdf->writeHTML('</tbody>');
            $this->mpdf->writeHTML('</table>');
            $this->mpdf->writeHTML('</div>');
        }
        return $this;
    }

}
