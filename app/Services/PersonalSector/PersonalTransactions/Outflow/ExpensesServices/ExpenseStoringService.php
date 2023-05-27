<?php

namespace App\Services\PersonalSector\PersonalTransactions\OutFlow\ExpenseesServices;

use Exception;

use App\CustomLibs\ValidatorLib\Validator;
use App\Http\Requests\PersonalSector\PersonalTransactions\OutFlow\StoringExpenseRequest;
use App\Models\PersonalSector\PersonalTransactions\Outflow\Expense;
use App\Services\WorkSector\Interfaces\NeedToStoreDateFields;
use App\Services\WorkSector\Interfaces\MustCreatedMultiplexed;
use App\Services\WorkSector\WorkSectorStoringService;

class ExpenseStoringService extends WorkSectorStoringService implements MustCreatedMultiplexed, NeedToStoreDateFields
{

    protected function getDefinitionCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Expense !";
    }

    protected function getDefinitionCreatingSuccessMessage(): string
    {
        return "The Expense Has Been Created Successfully !";
    }

    protected function getDefinitionModelClass(): string
    {
        return Expense::class;
    }

    protected function getRequestClass(): string
    {
        return StoringExpenseRequest::class;
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
            'user_id',
            "payment_date",
            "attachments",
            "notes",
            "amount",
            "paid_to",
            "category",
            "asset_id",
            "client_id",
            "client_po_id",
            "purchase_invoice_id",
            "expense_type_id",
            "currency_id",
            "payment_method_id",
            'expense_invoice',
            'accepted_at',
            'accepted_by',
            'rejected_at',
            'rejected_by',
        ];
    }

    public function getDateFieldNames(): array
    {
        return ["created_at", "updated_at"];
    }
    public function setRequestGeneralValidationRules(): Validator
    {
        return $this->validator->AllRules();
    }

    /**
     * @return Validator
     * @throws Exception
     */
    public function setSingleRowValidationRules(): Validator
    {
        return $this->validator->AllRules();
    }
}
