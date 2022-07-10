<?php

declare(strict_types=1);


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Arr;

class BookResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [

            "id" => $this->id,
            "isbn" => $this->isbn,
            "title" => $this->title,
            "description" => $this->description,
            "authors" => $this->authors,
            'review' => [
                'avg' => (int) $this->reviews()->avg('review') ? round($this->reviews()->avg('review')) : 0,
                'count' => (int) $this->reviews()->count()
            ]


        ];
    }
}
