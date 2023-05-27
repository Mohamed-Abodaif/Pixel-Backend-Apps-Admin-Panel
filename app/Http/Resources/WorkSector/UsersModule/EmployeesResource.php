<?php

namespace App\Http\Resources\WorkSector\UsersModule;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //$data = parent::toArray($request);
        //return $data;
        return [
            'id' => $this->id,
            'name' => $this->name ?? 'Unknown'
        ];
    }
}
