<?php

namespace App\Jobs\ExportersManagementJobs;

use App\CustomLibs\ExportersManagement\Exporter\Exporter;
use App\Exceptions\JsonException;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class HugeDataExporterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Request $Request;
    private array $RequestQueryStringArray = [];
    private array $RequestPostData = [];
    private ?Collection $DataCollection = null;

    private string $ExporterClass;
    private Exporter $exporter;

    /**
     * @param string $ExporterClass
     * @param Request $request
     * @throws JsonException
     */
    public function __construct(string $ExporterClass , Request $request )
    {
        $this->RequestQueryStringArray = $request->query->all();
        $this->RequestPostData = $request->all();
        $this->setExporterClass($ExporterClass);
    }

    public function setDataCollection(Collection $collection) : self
    {
        $this->DataCollection = $collection;
        return $this;
    }

    private function updateRequest(Request $request) : Request
    {
        return $request->merge([ ...$this->RequestPostData , ...$this->RequestQueryStringArray] );
    }

    /**
     * @param string $ExporterClass
     * @return $this
     * @throws JsonException
     */
    private function setExporterClass(string $ExporterClass) : self
    {
        if(!class_exists($ExporterClass)){throw new JsonException("The Given Exporter Class Is Not Defined !");}

        $exporter = new $ExporterClass();
        if(!$exporter instanceof Exporter){throw new JsonException("The Given Exporter Class Is Not Valid Exporter Class !");}
        $this->ExporterClass = $ExporterClass ;

        return $this;
    }

    private function setExporter() : self
    {
        $this->exporter = new $this->ExporterClass;
        return $this;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function handle(Request $request)
    {
        $this->setExporter();
        $this->exporter->setCustomRequest( $this->updateRequest($request) );
        if($this->DataCollection != null) {$this->exporter->setCustomDataCollection($this->DataCollection);}
        $this->exporter->exportingFunAliasForJob();

        //If No Exception Is Thrown An Success Notification Will Be Sent With The Required Data Files Links
    }
}
