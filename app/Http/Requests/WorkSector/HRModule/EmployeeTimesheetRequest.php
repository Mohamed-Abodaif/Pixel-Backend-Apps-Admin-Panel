<?php

namespace App\Http\Requests\WorkSector\HRModule;

use App\Http\Requests\BaseFormRequest;


class EmployeeTimesheetRequest extends BaseFormRequest
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
                "timesheet_sc_id" => "exists:employees_timesheet_sub_categories,id",
                "client_id" => "exists:clients,id",
                "client_po_id" => "exists:client_orders,id",
                "country_id" => "exists:countries,id",
                "vendor_id" => "exists:vendors,id",
                "vendor_po_id" => "exists:vendor_orders,id",
                "start_date" => "date",
                "end_date" => "date",
                // "start_time" =>"required" ,
                // "end_time" =>"required" ,
            ];
        } elseif (request()->isMethod('PUT')) {
            return [

                "timesheet_sc_id" => "exists:employees_timesheet_sub_categories,id",
                "client_id" => "exists:clients,id",
                "client_po_id" => "exists:client_orders,id",
                "country_id" => "exists:countries,id",
                "vendor_id" => "exists:vendors,id",
                "vendor_po_id" => "exists:vendor_orders,id",
                "calendar" => "required",
                "start_date" => "date",
                "end_date" => "date",
            ];
        }
    }

    public function passedValidation()
    {
        // $this->merge(
        //     [
        //         'attachments' => isset($this->attachments)? json_encode($this->attachments):null
        //     ]
        // );
    }
}
