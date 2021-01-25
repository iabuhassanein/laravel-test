<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class PrankIdeaResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title,
            'likes' => $this->likes,
            'our_likes' => $this->our_likes,
            'our_dislikes' => $this->our_dislikes,
            'our_favorites' => $this->our_favorites,
            'views' => $this->views,
            'sent' => $this->sent,
            'our_sent' => $this->our_sent,
            'prank_of_the_week' => $this->prank_of_the_week,
            'tags' => $this->tags(),
            'description' => $this->description,
            'share_text' => $this->share_text,
            'img_url' => $this->img_url,
            'default_character' => $this->when($this->hasDefaultCharacter(), CharacterResource::make($this->defaultCharacter)),
        ];
    }
}
