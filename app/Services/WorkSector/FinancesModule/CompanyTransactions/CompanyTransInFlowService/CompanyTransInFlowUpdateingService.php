<?php
        namespace App\Services\WorkSector\FinancesModule\CompanyTransactions\CompanyTransInFlow;

        use App\Services\WorkSector\WorkSectorUpdatingService;

        class CompanyTransInFlowUpdatingService extends WorkSectorUpdatingService
        {

            protected function getDefinitionUpdatingFailingErrorMessage(): string
            {
                return '';
            }

            protected function getDefinitionUpdatingSuccessMessage(): string
            {
                return '';
            }

            protected function getRequestClass(): string
            {
                return '';
            }
        }
        