<?php

namespace App\Services\WorkSector\FinancesModule\SalesInvoicesService;

use App\Http\Requests\WorkSector\FinancesModule\SalesInvoices\SaleInvoiceRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class SaleInvoicesUpdatingService extends UpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return 'Failed To Update The Given SaleInvoices !';
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return 'The SaleInvoices Has Been Created Successfully !';
    }

    protected function getRequestClass(): string
    {
        return SaleInvoiceRequest::class;
    }
}
