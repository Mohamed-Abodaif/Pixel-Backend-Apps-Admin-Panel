<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Jobs;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Exporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Notifications\ExportedDataFilesNotifier;
use App\Exceptions\JsonException;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class HugeDataExporterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Authenticatable $notifiable;
    private Request $Request;
    private array $RequestQueryStringArray = [];
    private array $RequestPostData = [];
    private Collection | LazyCollection | null $DataCollection = null;

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
        $this->setExporterClass($ExporterClass)->setNotifiable();
    }

    public function setDataCollection(Collection | LazyCollection | null $collection) : self
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

    private function setNotifiable() : self
    {
        $this->notifiable = User::offset(1)->take(1)->get()->first();
            //auth("api")->user();
        return $this;
    }

    private function setExporter() : self
    {
        $this->exporter = new $this->ExporterClass;
        return $this;
    }

    protected function NotifyExportedData(string $ExportedDataDownloadingPath) : self
    {
        $this->notifiable->notify(new ExportedDataFilesNotifier($ExportedDataDownloadingPath));
        return $this;
    }

    /**
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function handle(Request $request) : void
    {
        $this->setExporter();
        $this->exporter->setCustomRequest( $this->updateRequest($request) );
        if($this->DataCollection != null) {$this->exporter->setCustomDataCollection($this->DataCollection);}
        $ExportedDataDownloadingPath = $this->exporter->exportingJobFun();
        $this->NotifyExportedData($ExportedDataDownloadingPath);
    }
}
