<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this);
        return [
            'total_entries' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'last_pages' => $this->lastPage(),
            'previous_page' => $this->currentPage() -1 ==0 ?null: $this->currentPage() -1,
            'next_page' => $this->currentPage() +1 > $this->lastPage() ? null: $this->currentPage() +1
        ];
    }
}
