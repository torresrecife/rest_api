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
        $data = [];
        $data['id'] = $this->id;
        $data['name'] = $this->name;
        $data['description'] = $this->description;
        $data['filename'] = $this->filename;
        $data['filesize'] = $this->filesize;
        $data['type'] = $this->type;
        if($this->content){
            $data['content'] = (substr($this->content, 0, 500) . '...'); //limits the display characters
        }
        $data['user_id'] = $this->user_id;
        $data['created_at'] = $this->created_at->format('d/m/Y');
        $data['updated_at'] = $this->updated_at->format('d/m/Y');

        return [
            $data
        ];
    }
}
