<?php
namespace App\Repositories;

use App\Models\Warehouse;

class WarehouseRepository
{
    public function create($dto)
    {
        return Warehouse::create([
            'name'     => $dto->name,
            'location' => $dto->location,
        ]);
    }

    public function all()
    {
        return Warehouse::latest()->get();
    }
    public function paginate($perPage)
    {
        return Warehouse::latest()->paginate($perPage);
    }
    public function get_warehouse_inventories($id, $page, $perPage, $filters)
    {
        $warehouse = Warehouse::findOrFail($id);
        // return $warehouse->invi
        // dd($warehouse->inventoryItems()->where('name', 'like', "%item%")->get());
        return $warehouse->inventoryItems()
            ->when($filters['inventory_name'] ?? null, function ($query) use ($filters) {
                $query->where('name', 'like', "%{$filters['inventory_name']}%");
            })
            ->when($filters['inventory_sku'] ?? null, function ($query) use ($filters) {
                $query->where('sku', $filters['inventory_sku']);
            })
            ->when($filters['min_price'] ?? null, function ($query) use ($filters) {
                $query->where('price', '>=', $filters['min_price']);
            })
            ->when($filters['max_price'] ?? null, function ($query) use ($filters) {
                $query->where('price', '<=', $filters['max_price']);
            })
        // ->orderByPivot('quantity', 'desc')
            ->latest()
            ->paginate($perPage);
    }
}
