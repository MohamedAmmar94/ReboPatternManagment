<?php
namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'inventory'  => new InventoryItemResource($this->inventoryItem),
            'warehouse'  => new WarehouseResource($this->warehouse),
            'quantity'   => $this->quantity,
            'created_at' => $this->created_at,
        ];
    }
}
