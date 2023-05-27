<?php

namespace App\CustomLibs\ExportersManagement\ExporterTypes\PDFExporter;

use App\CustomLibs\ExportersManagement\Exporter\PresentationDataExporter;
use Exception;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * بالنسبة لل pdf :
 * - لازم نعمل تبديل بين صفحتين view بناء على المعطيات مع وضع صفحة افتراضية
 * صفحة للجدول وصفحة لبطاقات في كل صفحة
 *
 * بالنسبة لآلية الضغط بدي حطها آلية منفصلة وخلي الكلاس اللي يحتاجها يستخدمها وممكن تكون كلاس منفصل اسمه compressor
 *
 * بالنسبة للملفات اللي بتنحفظ ويتم ارسال روابطها بايميل لازم يتم مسحها بعد مدة زمنية معينة
 * ولازم تظبط البروكسي تبع رفع الملفات عال s3 ليتعامل مع ملفات مانهم uploadedFile
 *
 * لازم تظبط عرض الخلايا بالاكسل
 *
 *- بالنسبة لل job  :
 * لازم ظبط تنفيذ العمليات بالخلفية
 * فيما لو كانت كبيرة بالنسبة للملفات كلها
 * وفيما لو كانت صغيرة بس مع ملفات مع ال csv
 */

abstract class PDFExporter extends PresentationDataExporter
{
    use TemplateBuildingMethods ;

    protected Mpdf $mpdf;

    abstract protected function getTitle() : string;

    /**
     * @throws MpdfException
     * @throws Exception
     */
    function __construct()
    {
        parent::__construct();
        $this->initMPDF()->setDefaultStyle()->setDefaultTitle()->setDefaultAuthor()->setDefaultFooter()
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

    /**
     * @return $this
     * @throws MpdfException
     */
    protected function setDefaultStyle() : self
    {
        $style = "body {font-family: 'Open Sans', sans-serif;}
                        h1 {font-family: 'Roboto', sans-serif;}
                        .container{margin: auto; width: 80%; height: 100vh; display: flex; align-items: center; justify-content: center;}
                        table{ width: 100%; height: 100%; text-align:center;}
                         th{color: white; background-color: #84CC16}
                         td{white-space: nowrap}";
        $this->mpdf->WriteHTML($style,  HTMLParserMode::HEADER_CSS);
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
        $this->mpdf->SetTitle($this->getTitle());
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

    /**
     * @return StreamedResponse
     * @throws MpdfException
     */
    protected function exportingFun()
    //: StreamedResponse
    {
        $this->buildTemplate();
        return $this->mpdf->Output($this->fileName . "pdf", 'D');
    }

}
