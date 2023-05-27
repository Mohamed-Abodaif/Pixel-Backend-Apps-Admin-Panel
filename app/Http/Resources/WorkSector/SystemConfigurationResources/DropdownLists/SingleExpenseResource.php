<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $has_discussion=$this->disscussions->count() > 0 ? true :false;
        return 
        [
            "status"=> "success",
            "data"=> [
                "item"=> parent::toArray($request),
                'has_discussion'=>$has_discussion
            ]
        ];

    }
}
