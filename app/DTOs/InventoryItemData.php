<?php
namespace App\DTOs;

class InventoryItemData
{
    public function __construct(
        public string $name,
        public string $sku,
        public float $price,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->name,
            sku: $request->sku,
            price: $request->price,
        );
    }
}
