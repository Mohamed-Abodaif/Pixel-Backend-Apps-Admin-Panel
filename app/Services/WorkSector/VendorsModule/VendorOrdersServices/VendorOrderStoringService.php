<?php

namespace App\Services\WorkSector\VendorsModule\VendorOrderServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\WorkSector\VendorsModule\StoringVendorOrderRequest;
use App\Models\WorkSector\VendorsModule\VendorOrder;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;
use App\Services\WorkSector\WorkSectorStoringService;

class VendorOrderStoringService extends WorkSectorStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Vendor Order !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Vendor Order Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return VendorOrder::class;
    }

    protected function getRequestClass(): string
    {
        return StoringVendorOrderRequest::class;
    }

    //There Is No Key Will Be Used If IsItMultipleCreation returns false
    //Because Model itself will determine the fillable values from request main data array
    public function getRequestDataKey(): string
    {
        return "items";
    }

    protected function getFillableColumns(): array
    {
        return [
            'order_name',
            'order_number',
            'date',
            'delivery_date',
            'vendor_id',
            'department_id',
            'payments_terms_id',
            'currency_id',
            'po_total_value',
            'po_net_value',
            'po_sales_taxes_value',
            'po_add_and_discount_taxes_value',
            'po_attachments',
            'notes'
        ];
    }

    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }
    public function setRequestGeneralValidationRules(): Validator
    {
        return $this->validator->ExceptRules(["order_name"]);
    }

    /**
     * @return Validator
     * @throws Exception
     */
    public function setSingleRowValidationRules(): Validator
    {
        return $this->validator->OnlyRules(["order_name"]);
    }
}
