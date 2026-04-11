<?php
namespace App\Http\Resources\Api;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehouseInventoriesResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $pagination = new PaginationResource($this);
        return [
            'error'      => false,
            'message'    => "success",
            'data'       => $this->collection,
            'pagination' => $pagination,
        ];
    }
}
