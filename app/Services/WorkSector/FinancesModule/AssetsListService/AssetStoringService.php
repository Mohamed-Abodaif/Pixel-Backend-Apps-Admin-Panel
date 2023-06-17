<?php

namespace App\Services\WorkSector\FinancesModule\AssetsListService;

use App\Models\WorkSector\FinanceModule\AssetsList\Asset;
use App\Http\Requests\WorkSector\FinancesModule\AssetsList\AssetsRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;

class AssetStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Asset !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Asset Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return Asset::class;
    }

    protected function getRequestClass(): string
    {
        return AssetsRequest::class;
    }
}
