<?php
        namespace App\Services\WorkSector\FinancesModule\SalesInvoicesStatus;

        use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;
        // use App\Http\Requests\WorkSector\FinancesModule\SalesInvoicesStatus\SalesInvoiceStatusRequest;
        use App\Models\WorkSector\FinancesModule\SalesInvoicesStatus\SalesInvoiceStatus;
        class SalesInvoiceStatusStoringService extends SingleRowStoringService
        {

            protected function getDefinitionCreatingFailingErrorMessage(): string
            {
                return 'Failed To Create The Given SalesInvoiceStatus !';
            }

            protected function getDefinitionCreatingSuccessMessage(): string
            {
                return 'The SalesInvoiceStatus Has Been Created Successfully !';
            }

            protected function getDefinitionModelClass(): string
            {
                return SalesInvoiceStatus::class;
            }

            protected function getRequestClass(): string
            {
                return SalesInvoiceStatusRequest::class;
            }

        }

        