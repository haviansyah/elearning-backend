<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Quiz extends JsonResource
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
            "description" => $this->description,
            "due_date" => $this->due_date,
            "duration" => $this->duration,
            "random_question" => (bool) $this->random_question,
            "question_type" => $this->question_type,
            "questions" => Question::collection($this->questions) ?? null
        ];
    }
}
