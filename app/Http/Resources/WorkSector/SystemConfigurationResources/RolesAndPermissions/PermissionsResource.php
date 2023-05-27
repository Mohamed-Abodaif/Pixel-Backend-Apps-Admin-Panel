<?php

namespace App\Http\Resources\WorkSector\SystemConfigurationResources\RolesAndPermissions;

use App\Classes\phpCipher;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class PermissionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //        $encyrpt_algorithm=new phpCipher();
        //
        //        $enc_permission=$encyrpt_algorithm->encrypt(env('ENCY_KEY'),$this->name );

        return [
            "name" => $this->name
        ];
    }
}
