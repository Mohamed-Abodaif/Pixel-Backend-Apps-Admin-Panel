<?php

namespace App\Http\Resources\WorkSector\HRModule;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeesTimesheetResource extends JsonResource
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
        $data['title'] = $this->subCategoryTimesheet->name;

        $start  = new Carbon("$this->start_date $this->start_time");
        $end  = new Carbon("$this->end_date $this->end_time");

        //$diff=$start->diffInHours($end) . ':' . $start->diff($end)->format('%I');
        //dd($diff,$this->id,"$this->start_date $this->start_time","$this->end_date $this->end_time");

        $data['workingTime'] = ['hours' => $diff = $start->diffInHours($end), 'minutes' => (int)$start->diff($end)->format('%I')];
        return $data;
    }
}
