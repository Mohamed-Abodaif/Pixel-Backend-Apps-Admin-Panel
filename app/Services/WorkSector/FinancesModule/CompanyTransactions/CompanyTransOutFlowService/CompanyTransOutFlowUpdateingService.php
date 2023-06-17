<?php
        namespace App\Services\WorkSector\FinancesModule\CompanyTransactions\CompanyTransOutFlow;

        use App\Services\WorkSector\WorkSectorUpdatingService;

        class CompanyTransOutFlowUpdatingService extends WorkSectorUpdatingService
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
        