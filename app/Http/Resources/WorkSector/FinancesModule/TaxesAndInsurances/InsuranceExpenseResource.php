<?php

namespace App\Http\Resources\WorkSector\FinancesModule\TaxesAndInsurances;

use Illuminate\Http\Resources\Json\JsonResource;

class InsuranceExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
