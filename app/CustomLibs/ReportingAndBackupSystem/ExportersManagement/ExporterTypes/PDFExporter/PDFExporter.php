<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\PDFExporter;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Exporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\ExportersMainTypes\PresentationDataExporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\ExporterTypes\PDFExporter\Responders\PDFStreamingResponder;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders\StreamingResponder;
use Exception;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

abstract class PDFExporter extends PresentationDataExporter
{
    use TemplateBuildingMethods ;

    protected Mpdf $mpdf;
    protected string $TemplateBladeName;

    public const TABLE_TEMPLATE = "Reports.PDFTemplates.TableTemplate";
    public const CARD_TEMPLATE = "Reports.PDFTemplates.CardsTemplate";

    /**
     * @throws MpdfException
     * @throws Exception
     */
    function __construct()
    {
        parent::__construct();
        $this->initMPDF()->setTemplateBladeName()->setDefaultTitle()->setDefaultAuthor()->setDefaultFooter()
             ->setDefaultHeaderLine();
    }

    /**
     * @return $this
     */
    protected function initMPDF() : self
    {
        $this->mpdf = new Mpdf();
        return $this;
    }

    protected function setTemplateBladeName() : self
    {
        $this->TemplateBladeName = PDFExporter::TABLE_TEMPLATE;
        return $this;
    }

    /**
     * @param string $style
     * @return $this
     * @throws Exception
     */
    public function setCustomStyle(string $style = "") : self
    {
        if($style == ""){throw new Exception("PDF Exported File's Style Can't Be Empty String !");}
        $this->mpdf->WriteHTML($style,  HTMLParserMode::HEADER_CSS);
        return $this;
    }

    /**
     * @return $this
     */
    protected function setDefaultTitle() :self
    {
        $this->mpdf->SetTitle($this->getDocumentTitle());
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function setCustomTitle(string $title = "") :self
    {
        if($title == ""){throw new Exception("PDF Exported File's Title Can't Be Empty String !");}
        $this->mpdf->SetTitle($title);
        return $this;
    }

    protected function setDefaultAuthor() : self
    {
        $this->mpdf->SetAuthor('IGS Management System');
        return $this;
    }

    /**
     * @param string $author
     * @return $this
     * @throws Exception
     */
    public function setCustomAuthor(string $author = "") : self
    {
        if($author == ""){throw new Exception("PDF Exported File's Author Can't Be Empty String !");}
        $this->mpdf->SetAuthor($author);
        return $this;
    }

    /**
     * @return $this
     */
    protected function setDefaultFooter() : self
    {
        $this->mpdf->setFooter('Document Title|{DATE F j, Y}|{PAGENO}');
        return $this;
    }

    /**
     * @param string $footer
     * @return $this
     * @throws Exception
     */
    public function setCustomFooter(string $footer = "") : self
    {
        if($footer == ""){throw new Exception("PDF Exported File's Footer Can't Be Empty String !");}
        $this->mpdf->setFooter($footer);
        return $this;
    }

    /**
     * @return $this
     */
    protected function setDefaultHeaderLine() : self
    {
        $this->mpdf->defaultheaderline = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function setCustomDefaultHeaderLine(bool $DefaultHeaderLineStatus = false) : self
    {
        $this->mpdf->defaultheaderline = $DefaultHeaderLineStatus;
        return $this;
    }

    protected function getStreamingResponder(): StreamingResponder
    {
        return new PDFStreamingResponder();
    }

    /**
     * @return Exporter
     * @throws MpdfException
     */
    protected function setStreamingResponderResponseProps() : Exporter
    {
        $this->buildTemplate();
        /** @var PDFStreamingResponder $this->responder */
        $this->responder->setMPDF($this->mpdf);
        return $this;
    }

    protected function getDataFileExtension() : string
    {
        return "pdf";
    }

    /**
     * @return string
     * @throws MpdfException
     * @throws Exception
     */
    protected function setDataFileToExportedFilesProcessor() : string
    {
        return $this->filesProcessor->HandleTempFileContentToCopy(
                    $this->mpdf->output($this->fileFullName , "S"),
                    $this->fileFullName
                )->copyToTempPath();
    }

}
