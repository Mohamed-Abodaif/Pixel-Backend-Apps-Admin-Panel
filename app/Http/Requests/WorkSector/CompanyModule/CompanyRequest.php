<?php

namespace App\Http\Requests\WorkSector\CompanyModule;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseFormRequest;

class CompanyRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if (request()->isMethod('post')) {
            return [
                'name' => ['required', 'string'],
                'company_sector' => ['required', 'string'],
                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'logo' => ['string'],
                'country_id' => ['required', 'integer', 'exists:countries,id'],
                'employees_no' => ['required', 'integer'],
                'branches_no' => ['required', 'integer'],
              //  'package_status' => ['nullable', Rule::in(['Basic', 'Upgraded-no-Due', 'Upgraded-in-Due', 'Upgraded-over-Due'])],
                'package_id' => ['nullable', 'exists:packages,id'],
                'dates' => ['nullable', 'string'],
                'admin_email' => ['required', 'email', 'unique:companies,admin_email'],
                'billing_address' => ['nullable', 'string'],
                'company_tax_type' => ['nullable', Rule::in(['free_zone_client', 'local_client', 'international_client'])],
                'contacts'=>'required|array|min:1'
            ];
        } elseif (request()->isMethod('PUT')) {
            return [
                'id' => ['required', 'integer', 'exists:companies,id'],
                'name' => ['required', 'string'],
                'company_sector' => ['required', 'string'],
                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'logo' => ['required', 'string'],
                'country_id' => ['required', 'integer', 'exists:countries,id'],
                'employees_no' => ['required', 'string'],
                'branches_no' => ['required', 'string'],
                'package_status' => ['nullable', Rule::in(['Basic', 'Upgraded-no-Due', 'Upgraded-in-Due', 'Upgraded-over-Due'])],
                'package_id' => ['nullable', 'exists:packages,id'],
                'dates' => ['nullable', 'string'],
                'billing_address' => ['nullable', 'string'],
                'company_tax_type' => ['nullable', Rule::in(['free_zone_client', 'local_client', 'international_client'])],
            ];
        }
    }
}



// namespace App\Http\Requests\WorkSector\FinancesModule\CompanyTransactions;

// use App\Http\Requests\BaseFormRequest;


// class CompanyRequest extends BaseFormRequest
// {
//     /**
//      * Determine if the user is authorized to make this request.
//      *
//      * @return bool
//      */
//     public function authorize()
//     {
//         return true;
//     }

//     /**
//      * Get the validation rules that apply to the request.
//      *
//      * @return array
//      */
//     public function rules()
//     {

//         if (request()->isMethod('post')) {
//             return [
//                 'name' => 'required',
//                 'company_sector_id' => 'required|exists:company_sectors,id'
//             ];
//         } elseif (request()->isMethod('PUT')) {
//             return [
//                 'company_sector_id' => 'exists:companies_sectors,id'
//             ];
//         }
//     }
// }