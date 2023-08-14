<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LogHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "description" => $this->description,
            "news_id" => $this->news_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,

            "news" => new NewsResource($this->whenLoaded("news"))
        ];
    }
}
