<?php
        namespace App\Services\WorkSector\FinancesModule\TaxesAndInsurances\InsurancType;

        use App\Services\CoreServices\CRUDServices\CRUDServiceTypes\StoringServices\SingleRowStoringService;
        // use App\Http\Requests\WorkSector\FinancesModule\TaxesAndInsurances\InsurancType\InsurancTypeRequest;
        use App\Models\WorkSector\FinancesModule\TaxesAndInsurances\InsurancType\InsurancType;
        class InsurancTypeStoringService extends SingleRowStoringService
        {

            protected function getDefinitionCreatingFailingErrorMessage(): string
            {
                return 'Failed To Create The Given InsurancType !';
            }

            protected function getDefinitionCreatingSuccessMessage(): string
            {
                return 'The InsurancType Has Been Created Successfully !';
            }

            protected function getDefinitionModelClass(): string
            {
                return InsurancType::class;
            }

            protected function getRequestClass(): string
            {
                return InsurancTypeRequest::class;
            }

        }

        