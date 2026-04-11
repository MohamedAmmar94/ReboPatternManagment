<?php
namespace App\Repositories;

use App\Models\InventoryItem;

class InventoryRepository
{
    public function create($dto)
    {
        return InventoryItem::create([
            'name'  => $dto->name,
            'sku'   => $dto->sku,
            'price' => $dto->price,
        ]);
    }

    public function list($per_page)
    {
        return InventoryItem::query()->latest()->paginate($per_page);
    }
    public function paginateWithFilters($name, $min_price, $max_price, $per_page)
    {
        return InventoryItem::query()
            ->search($name, $min_price, $max_price)
            ->paginate($per_page);
    }
}
