<?php
namespace App\Repositories;

use App\Models\Stock;

class StockRepository
{
    public function getStock(int $warehouseId, int $itemId)
    {
        return Stock::where([
            'warehouse_id'      => $warehouseId,
            'inventory_item_id' => $itemId,
        ])->lockForUpdate()->first();
    }

    public function increase(int $warehouseId, int $itemId, int $qty)
    {
        $stock = Stock::firstOrCreate([
            'warehouse_id'      => $warehouseId,
            'inventory_item_id' => $itemId,
        ]);

        $stock->increment('quantity', $qty);

        return $stock;
    }

    public function decrease($stock, int $qty)
    {
        $stock->decrement('quantity', $qty);

        return $stock;
    }
    public function setStock($dto)
    {
        return Stock::updateOrCreate(
            [
                'warehouse_id'      => $dto->warehouseId,
                'inventory_item_id' => $dto->inventoryItemId,
            ],
            [
                'quantity' => $dto->quantity,
            ]
        );
    }
    public function paginateWithFilters($inventoryItemId, $warehouseId, $perPage, $filters)
    {
        return Stock::when($inventoryItemId, function ($query) use ($inventoryItemId) {
            $query->where('inventory_item_id', $inventoryItemId);
        })
            ->when($warehouseId, function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })
            ->when(array_filter($filters), function ($query) use ($filters) {
                $query->whereHas('inventoryItem', function ($q) use ($filters) {

                    $q->when($filters['inventory_name'] ?? null, function ($q2, $value) {
                        $q2->where('name', 'like', "%{$value}%");
                    });

                    $q->when($filters['inventory_sku'] ?? null, function ($q2, $value) {
                        $q2->where('sku', $value);
                    });

                    $q->when($filters['min_price'] ?? null, function ($q2, $value) {
                        $q2->where('price', '>=', $value);
                    });

                    $q->when($filters['max_price'] ?? null, function ($q2, $value) {
                        $q2->where('price', '<=', $value);
                    });

                });
            })
            ->latest()
            ->paginate($perPage);
    }
}
