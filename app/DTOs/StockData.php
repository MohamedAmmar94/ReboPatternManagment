<?php
namespace App\DTOs;

class StockData
{
    public function __construct(
        public int $warehouseId,
        public int $inventoryItemId,
        public int $quantity,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            warehouseId: $request->warehouse_id,
            inventoryItemId: $request->inventory_item_id,
            quantity: $request->quantity,
        );
    }
}
