<?php
namespace App\Services\WorkSector\FinancesModule\AssetsListService;

use App\Http\Requests\WorkSector\FinancesModule\AssetsList\UpdateAssetsRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\UpdatingServices\UpdatingService;

class AssetUpdatingService extends UpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Asset !";
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return "The Asset Has Been Updated Successfully !";
    }

    protected function getRequestClass(): string
    {
        return UpdateAssetsRequest::class;
    }
}
        