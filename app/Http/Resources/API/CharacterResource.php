<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class CharacterResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'is_influencer' => $this->is_influencer,
            'facebook_url' => $this->facebook_url,
            'youtube_url' => $this->youtube_url,
            'img_url' => $this->img_url,
        ];
    }
}
