<?php
namespace App\Http\Controllers\Api\V1;

use App\DTOs\StockData;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StockResource;
use App\Http\Resources\Api\StockResourceCollection;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StockController extends Controller
{
    public function __construct(private StockService $service)
    {}
    public function list(Request $request)
    {
        $perPage         = $request->per_page ?? 10;
        $inventoryItemId = $request->inventory_item_id;
        $warehouseId     = $request->warehouse_id;
        $filters         = [
            'inventory_name' => $request->inventory_name ?? null,
            'inventory_sku'  => $request->inventory_sku ?? null,
            'min_price'      => $request->min_price ?? null,
            'max_price'      => $request->max_price ?? null,
        ];
        $inventoryItems = $this->service->paginateWithFilters($inventoryItemId, $warehouseId, $perPage, $filters);
        return response()->json(new StockResourceCollection($inventoryItems));
    }
    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id'      => 'required|exists:warehouses,id',
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity'          => 'required|integer|min:0',
        ]);

        $dto        = StockData::fromRequest($request);
        $stock_data = new StockResource($this->service->setStock($dto));
        Cache::tags(["warehouse_{$request->warehouse_id}"])->flush();
        return response()->json(
            $stock_data
            , 201
        );
    }
    public function inventory(Request $request)
    {
        $warehouse_id = $request->warehouse_id ?? null;
        $page         = $request->page ?? 1;
        $perPage      = $request->per_page ?? 10;
        $filters      = [
            'inventory_name' => $request->inventory_name ?? null,
            'inventory_sku'  => $request->inventory_sku ?? null,
            'min_price'      => $request->min_price ?? null,
            'max_price'      => $request->max_price ?? null,
        ];
        $filters_string = md5(json_encode($filters));
        $cache_key      = "warehouse:{$warehouse_id}:stocks:page:{$page}:perPage:{$perPage}:filters:{$filters_string}";
        $data           = Cache::tags(["warehouse_{$warehouse_id}"])->remember($cache_key, 1800, function () use ($warehouse_id, $page, $perPage, $filters) {
            $paginatedData = $this->service->paginateWithFilters(null, $warehouse_id, $perPage, $filters);
            return new StockResourceCollection($paginatedData)->response()->getData(true);
        });
        //optional
        unset($data['links']);
        unset($data['meta']);
        return response()->json($data);
    }

}
