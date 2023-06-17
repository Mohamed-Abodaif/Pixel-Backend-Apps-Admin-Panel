<?php

namespace App\Services\WorkSector\FinancesModule\SalesInvoicesService;

use App\Models\WorkSector\FinanceModule\SalesInvoices\SalesInvoice;
// use App\Http\Requests\WorkSector\FinancesModule\SalesInvoices\SaleInvoicesRequest;
use App\Http\Requests\WorkSector\FinancesModule\SalesInvoices\SaleInvoiceRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class SaleInvoicesStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return 'Failed To Create The Given SaleInvoices !';
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return 'The SaleInvoices Has Been Created Successfully !';
    }

    protected function getDefinitionModelClass(): string
    {
        return SalesInvoice::class;
    }

    protected function getRequestClass(): string
    {
        return SaleInvoiceRequest::class;
    }
}
