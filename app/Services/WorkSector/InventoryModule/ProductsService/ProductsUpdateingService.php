<?php

namespace App\Services\WorkSector\InventoryModule\ProductsService;

use App\Services\WorkSector\WorkSectorUpdatingService;
use App\Http\Requests\WorkSector\InventoryModule\ProductRequest;

class ProductsUpdatingService extends WorkSectorUpdatingService
{

    protected function getDefinitionUpdatingFailingErrorMessage(): string
    {
        return 'Failed To Update The Given Products !';
    }

    protected function getDefinitionUpdatingSuccessMessage(): string
    {
        return 'The Products Has Been Updated Successfully !';
    }

    protected function getRequestClass(): string
    {
        return ProductRequest::class;
    }
}
