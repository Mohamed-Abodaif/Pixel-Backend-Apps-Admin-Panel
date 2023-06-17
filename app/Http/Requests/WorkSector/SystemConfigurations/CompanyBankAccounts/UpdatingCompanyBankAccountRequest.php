<?php

namespace App\Http\Requests\WorkSector\SystemConfigurations\CompanyBankAccounts;

use App\Http\Requests\WorkSector\SystemConfigurations\UpdatingRequest;
use App\Models\WorkSector\SystemConfigurationModels\CompanyBankAccount;

class UpdatingCompanyBankAccountRequest extends UpdatingRequest
{
    protected function getModelName(): string
    {
        return CompanyBankAccount::class;
    }

    protected function getTableName(): string
    {
        return "company_bank_accounts";
    }

    public function messages()
    {
        return [
            "items.*.account_name.required" => "Account Name Has Not Been Sent !",
                "items.*.account_name.string" => "Account Name Must Be String !",
                "items.*.account_name.max" => "Account Name Must Not Be Greater THan 255 Character !",
                "items.*.bank_name.required" => "Bank Account Name Has Not Been Sent !",
                "items.*.bank_name.string" => "Bank Account Name Must Be String !",
                "items.*.bank_name.max" => "Bank Account Name Must Not Be Greater THan 255 Character !",
                "items.*.status.boolean" => "Bank Account Status  Must Be Boolean",
                'items.*.bank_branch.string' => 'Bank Branch Name Must Be String !',
                'items.*.bank_branch.max' => 'Bank Branch Name Must Not Be Greater THan 255 Character !',
                'items.*.bank_branch.required' => 'Bank Branch Name Has Not Been Sent !',
                'items.*.country_id.required' => 'Country Id Has Not Been Sent !',
                'items.*.country_id.exists' => 'Country Id Not found Please Enter A Valid ID',
                'items.*.city_id.exists' => 'City Id Not found Please Ente A Valid ID',//optional
                'items.*.currency_id.required' => 'Currency Id Has Not Been Sent !',//required
                'items.*.currency_id.exists' => 'Currency Id Not found Please Ente A Valid ID',//required
                'items.*.bank_code_type.required' => 'bank code type Has Not Been Sent !',//required
                'items.*.bank_code_type.in' => 'bank code type It Must Be Use Swift Or Use Iban',//required
                'items.*.swift_code.required_if' => 'Swift Code Has Not Been Sent !',//required if
                'items.*.swift_code.string' => 'Swift Code Must Be String !',
                'items.*.account_number.required_if' => 'Account Number Has Not Been Sent !',
                'items.*.account_number.string' => 'Account Number Must Be String !',
                'items.*.iban_number.required_if' => 'Iban Number Has Not Been Sent !',
                'items.*.iban_number.string' => 'Iban Number Must Be String !',

                "items.*.swift_code.unique" => "Company Bank Swift  Is Already Stored In Our Database !",
                "items.*.iban_number.unique" => "Company Bank Iban Number Is Already Stored In Our Database !",
                "items.*.account_number.unique" => "Company Bank Account Number Is Already Stored In Our Database !",
        ];
    }

    public function rules($data){
        return [
            'items.*.account_name' => 'required|string|max:255',
            'items.*.bank_name' => 'required|string|max:255',
            'items.*.bank_branch' => 'required|string|max:255',
            'items.*.country_id' => 'required|exists:countries,id',
            'items.*.city_id' => 'nullable|exists:cities,id',
            'items.*.currency_id' => 'required|exists:currencies,id',
            'items.*.bank_code_type' => 'required|in:use_swift,use_iban',
            'items.*.swift_code' => 'nullable|required_if:bank_code_type,use_swift|string|max:255|unique:company_bank_accounts,swift_code',
            'items.*.account_number' => 'nullable|required_if:bank_code_type,use_swift|string|max:16|unique:company_bank_accounts,account_number',
            'items.*.iban_number' => 'nullable|required_if:bank_code_type,use_iban|string|max:255|unique:company_bank_accounts,iban_number',
            'items.*.status' => 'required|boolean',
        ];
    }

}