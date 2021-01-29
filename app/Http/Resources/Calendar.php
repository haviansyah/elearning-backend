<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Calendar extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $due_date = new Carbon($this->due_date);
        return [
            "title" => "Lesson \"".$this->name."\" Closed",
            "due_date" => $this->due_date,
            "start" => [
                $due_date->year,
                $due_date->month - 1,
                $due_date->day,
                $due_date->hour,
                $due_date->minute,
                $due_date->second
             ]
        ];
    }
}
