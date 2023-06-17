<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionTableSeederFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' =>
            $this->faker->unique()->randomElement(
                [
                    "read_personal-sector-dashboard",
                    "read_personal-sector-expenses",
                    "create_personal-sector-expenses",
                    "edit_personal-sector-expenses",
                    "delete_personal-sector-expenses",
                    "read_personal-sector-custody",
                    "create_personal-sector-custody",
                    "edit_personal-sector-custody",
                    "delete_personal-sector-custody",
                    "read_sc-dropdown-lists",
                    "create_sc-dropdown-lists",
                    "edit_sc-dropdown-lists",
                    "delete_sc-dropdown-lists",
                    "read_sc-roles-and-permissions",
                    "create_sc-roles-and-permissions",
                    "edit_sc-roles-and-permissions",
                    "delete_sc-roles-and-permissions",
                    "read_emm-signup-list",
                    "create_emm-signup-list",
                    "edit_emm-signup-list",
                    "delete_emm-signup-list",
                    "approve_emm-signup-list",
                    "read_emm-employees-list",
                    "create_emm-employees-list",
                    "edit_emm-employees-list",
                    "delete_emm-employees-list",
                    "read_cmm-clients-list",
                    "create_cmm-clients-list",
                    "edit_cmm-clients-list",
                    "delete_cmm-clients-list",
                    "read_cmm-clients-quotations",
                    "create_cmm-clients-quotations",
                    "edit_cmm-clients-quotations",
                    "delete_cmm-clients-quotations",
                    "approve_cmm-clients-quotations",
                    "read_cmm-clients-orders",
                    "create_cmm-clients-orders",
                    "edit_cmm-clients-orders",
                    "delete_cmm-clients-orders",
                    "read_vmm-vendors-list",
                    "create_vmm-vendors-list",
                    "edit_vmm-vendors-list",
                    "delete_vmm-vendors-list",
                    "read_vmm-vendors-orders",
                    "create_vmm-vendors-orders",
                    "edit_vmm-vendors-orders",
                    "delete_vmm-vendors-orders",
                    "read_sfm-finances-summary",
                    "create_sfm-finances-summary",
                    "edit_sfm-finances-summary",
                    "delete_sfm-finances-summary",
                    "read_sfm-purchase-invoices",
                    "create_sfm-purchase-invoices",
                    "edit_sfm-purchase-invoices",
                    "delete_sfm-purchase-invoices",
                    "approve_sfm-purchase-invoices",
                    "read_sfm-sales-invoices",
                    "create_sfm-sales-invoices",
                    "edit_sfm-sales-invoices",
                    "delete_sfm-sales-invoices",
                    "approve_sfm-sales-invoices",
                    "read_sfm-assets-list",
                    "create_sfm-assets-list",
                    "edit_sfm-assets-list",
                    "delete_sfm-assets-list",
                    "read_sfm-taxes-and-insurances",
                    "create_sfm-taxes-and-insurances",
                    "edit_sfm-taxes-and-insurances",
                    "delete_sfm-taxes-and-insurances",
                    "read_sfm-investor",
                    "create_sfm-investor",
                    "edit_sfm-investor",
                    "delete_sfm-investor",
                ]
            ),

            'guard_name' =>
            $this->faker->randomElement(
                [
                    'api'
                ]
            ),

            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),


        ];
    }
}
