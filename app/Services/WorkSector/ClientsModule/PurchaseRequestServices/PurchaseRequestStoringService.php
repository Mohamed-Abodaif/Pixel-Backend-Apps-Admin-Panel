<?php

namespace App\Services\WorkSector\ClientsModule\PurchaseRequestServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\WorkSector\ClientsModule\StoringPurchaseRequestRequest;
use App\Models\WorkSector\ClientsModule\PurchaseRequest;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;
use App\Services\WorkSector\WorkSectorStoringService;

class PurchaseRequestStoringService extends WorkSectorStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Purchase Request !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Purchase Request Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return PurchaseRequest::class;
    }

    protected function getRequestClass(): string
    {
        return StoringPurchaseRequestRequest::class;
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
            'date',
            'delivery_date',
            'client_id',
            'order_number',
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
