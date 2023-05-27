<?php

namespace App\Http\Resources\PersonalSector\PersonalTransactions\Inflow;

use Illuminate\Http\Resources\Json\JsonResource;

class CustodiesResource extends JsonResource
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
