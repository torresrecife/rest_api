<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Files extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'filename' => $this->filename,
            'type' => $this->type,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'content' => (substr($this->content, 0, 500) . '...'), //limits the display characters
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
