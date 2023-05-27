<?php

namespace App\Http\Resources\WorkSector\FinancesModule\CompanyTransactions;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyTrasnactionResource extends JsonResource
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
