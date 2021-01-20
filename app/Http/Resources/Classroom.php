<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Classroom extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "subject" => $this->subject,
            "description" => $this->description,
            "max_student" => $this->max_student,
            "teacher" => $this->teacher,        
            "code" => $this->code,
            "participants" => $this->students,
        ];
    }
}
