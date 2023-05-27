<?php

namespace App\Http\Resources\WorkSector\SystemConfigurationResources\DropdownLists;

use Illuminate\Http\Resources\Json\JsonResource;

class CustodySenderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['user'] = $this->user();
        return $data;
    }
}
