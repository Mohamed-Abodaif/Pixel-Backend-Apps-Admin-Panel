<?php

namespace App\Http\Resources\WorkSector\UsersModule;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\WorkSector\countries\CountriesResource;

class UserProfileResource extends JsonResource
{
    private function handleCountryInfo(array $dataArrayToChange = []): array
    {
        if ($this->country != null) {
            $dataArrayToChange["country"] = new CountriesResource($this->country);
            unset($this->country);

            return $dataArrayToChange;
        }
        return [];
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = $this->handleCountryInfo();
        unset($this->user_id);
        return array_merge($data, parent::toArray($request));
    }
}
