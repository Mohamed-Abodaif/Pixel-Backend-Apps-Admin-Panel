<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Responders;

use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Exporter\Exporter;
use App\CustomLibs\ReportingAndBackupSystem\ExportersManagement\Jobs\HugeDataExporterJob;
use App\Exceptions\JsonException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\LazyCollection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobDispatcherJSONResponder  extends Responder
{
    protected string $exporterClass = "";
    protected ?HugeDataExporterJob $job = null;

    /**
     * @return $this
     * @throws JsonException
     */
    protected function initJob() : self
    {
        if($this->job){return $this;}
        if(!$this->exporterClass){throw new JsonException("There Is No Exporter Class Given To Job Object");}
        $this->job = new HugeDataExporterJob($this->exporterClass , request());
        return $this;
    }

    /**
     * @param Collection|LazyCollection|null $collection
     * @return $this
     * @throws JsonException
     */
    public function setDataCollection(Collection | LazyCollection | null $collection) : self
    {
        $this->initJob();
        $this->job->setDataCollection($collection);
        return $this;
    }

    /**
     * @param Exporter $exporter
     * @return $this
     * @throws JsonException
     */
    public function setExporterClass(Exporter $exporter): self
    {
        $this->exporterClass = get_class($exporter);
        $this->initJob();
        return $this;
    }

    /**
     * @return BinaryFileResponse|StreamedResponse|JsonResponse | string
     */
    public function respond():BinaryFileResponse | StreamedResponse | JsonResponse | string
    {
        dispatch($this->job);
        return Response::success([] , ["The Needed Data Is In Large Size , You Will Receive The Needed Data Files On Your Email !"]);
    }

}
