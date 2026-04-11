<?php
namespace App\DTOs;

class StockTransferData
{
    public function __construct(
        public int $fromWarehouseId,
        public int $toWarehouseId,
        public int $inventoryItemId,
        public int $quantity,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            fromWarehouseId: $request->from_warehouse_id,
            toWarehouseId: $request->to_warehouse_id,
            inventoryItemId: $request->inventory_item_id,
            quantity: $request->quantity,
        );
    }
}
