<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorResource;

class BookResource extends JsonResource
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
            'id' => $this->id,
            'authorId' => $this->author_id,
            'title' => $this->title,
            'description' => $this->description,
            'isbn' => $this->isbn,
            'price' => $this->price,
            'author' => new AuthorResource($this->author)
        ];
    }
}
