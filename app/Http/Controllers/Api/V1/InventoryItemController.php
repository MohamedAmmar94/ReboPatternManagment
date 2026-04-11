<?php
namespace App\Http\Controllers\Api\V1;

use App\DTOs\InventoryItemData;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\InventoryItemResource;
use App\Http\Resources\Api\InventoryItemResourceCollection;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function __construct(private InventoryService $service)
    {}
    public function list_with_filters(Request $request)
    {
        $perPage        = $request->per_page ?? 10;
        $name           = $request->name;
        $min_price      = $request->min_price;
        $max_price      = $request->max_price;
        $inventoryItems = $this->service->paginateWithFilters($name, $min_price, $max_price, $perPage);
        return response()->json(new InventoryItemResourceCollection($inventoryItems));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|unique:inventory_items,name',
            'sku'   => 'required|string|unique:inventory_items,sku',
            'price' => 'required|numeric',
        ]);
        $dto           = InventoryItemData::fromRequest($request);
        $inventoryItem = $this->service->create($dto);
        return response()->json(new InventoryItemResource($inventoryItem), 201);
    }

}
