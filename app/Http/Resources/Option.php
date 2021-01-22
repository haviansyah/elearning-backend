<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Option extends JsonResource
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
            "option" => $this->option,
            "is_true" => (bool) $this->isTrue,
        ];
    }
}
