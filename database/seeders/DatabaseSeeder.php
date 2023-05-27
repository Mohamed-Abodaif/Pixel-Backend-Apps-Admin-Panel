<?php

namespace Database\Seeders;

use Database\Factories\User\SignUpUserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
//            AssetsCategoryTableSeeder::class,
//            AssetExpenseTableSeeder::class,
//            AssetTableSeeder::class,
//            BonusTableSeeder::class,
//            ClientCategoryTableSeeder::class,
//            ClientOrderTableSeeder::class,
//            ClientPoExpenseTableSeeder::class,
//            ClientQuotationTableSeeder::class,
//            ClientTableSeeder::class,
//            ClientVisitExpenseTableSeeder::class,
//            CompanyOperationExpense::class,
            CountriesSeeder::class,
            PermissionsSeeder::class,
            RolesSeeder::class,
           // DepartmentsTableSeeder::class,
//            SignUpUserFactory::class,
//            EmployeeUserSeeder::class,
//            CurrenciesSeeder::class,
//            CustodyTableSeeder::class,
//            ExchangeExpenseTableSeeder::class,
//            ExpenseTypeTableSeeder::class,
//            InsuranceExpenseTableSeeder::class,
//            InsuranceTypeTableSeeder::class,
//            MarketingExpenseTableSeeder::class,
//            PaymentMethodsTableSeeder::class,
//            PaymentTermTableSeeder::class,
//            PurchaseExpenseTableSeeder::class,
//            PurchaseInvoiceTableSeeder::class,
//            TaxTypeTableSeeder::class,
//            TenderTableSeeder::class,
//            VendorTableSeeder::class,
//            PurchaseOrderTypeTableSeeder::class,
//            TaxExpenseTableSeeder::class,
        ]);
    }
}
