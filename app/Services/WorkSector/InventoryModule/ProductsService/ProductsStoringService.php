<?php

namespace App\Services\WorkSector\InventoryModule\ProductsService;

use App\Models\WorkSector\InventoryModule\Product;
use App\Http\Requests\WorkSector\InventoryModule\ProductRequest;
use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;
// use App\Http\Requests\WorkSector\InventoryModule\Products\ProductsRequest;

class ProductsStoringService extends SingleRowStoringService
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return 'Failed To Create The Given Products !';
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return 'The Products Has Been Created Successfully !';
    }

    protected function getDefinitionModelClass(): string
    {
        return Product::class;
    }

    protected function getRequestClass(): string
    {
        return ProductRequest::class;
    }
}
