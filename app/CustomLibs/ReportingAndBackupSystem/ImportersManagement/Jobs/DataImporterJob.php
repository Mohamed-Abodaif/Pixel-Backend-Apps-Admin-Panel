<?php

namespace App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Jobs;


use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Jobs\Traits\JobHandlingMethods;
use App\CustomLibs\ReportingAndBackupSystem\ImportersManagement\Jobs\Traits\JobSerializingMethods;
use App\Exceptions\JsonException;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DataImporterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //Own Traits
    use JobHandlingMethods , JobSerializingMethods;

    /**
     * @param string $importerClass
     * @throws JsonException
     */
    public function __construct(string $importerClass )
    {
        $this->setImporterClass($importerClass)->setNotifiable();
    }

    /**
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public function handle(Request $request)
    {
        $this->initImporter()->informToDeleteImportedDataFileAfterProcessing($this->DeleteImportedDataFileAfterProcessing)->importingJobFun();
        $this->SuccessfullyImportingDataNotifier();
    }
}
