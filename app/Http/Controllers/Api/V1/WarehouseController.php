<?php
namespace App\Http\Controllers\Api\V1;

use App\DTOs\WarehouseData;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WarehouseInventoriesResourceCollection;
use App\Http\Resources\Api\WarehouseResource;
use App\Http\Resources\Api\WarehouseResourceCollection;
use App\Services\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WarehouseController extends Controller
{
    public function __construct(private WarehouseService $service)
    {}

    public function list(Request $request)
    {
        $perPage = $request->per_page ?? 10;
        return response()->json(new WarehouseResourceCollection($this->service->paginate($perPage)));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|unique:warehouses,name',
            'location' => 'nullable|string',
        ]);

        $dto = WarehouseData::fromRequest($request);

        return response()->json(
            new WarehouseResource($this->service->create($dto)),
            201
        );
    }
    public function get_warehouse_inventory(Request $request, $id)
    {
        $page    = $request->page ?? 1;
        $perPage = $request->per_page ?? 10;
        $filters = [
            'inventory_name' => $request->inventory_name ?? null,
            'inventory_sku'  => $request->inventory_sku ?? null,
            'min_price'      => $request->min_price ?? null,
            'max_price'      => $request->max_price ?? null,
        ];
        $filters_string = md5(json_encode($filters));
        $cache_key      = "warehouse:{$id}:inventories:page:{$page}:perPage:{$perPage}:filters:{$filters_string}";
        $data           = Cache::tags(["warehouse_{$id}"])->remember($cache_key, 1800, function () use ($id, $page, $perPage, $filters) {
            $paginatedData = $this->service->get_warehouse_inventories($id, $page, $perPage, $filters);
            return new WarehouseInventoriesResourceCollection($paginatedData);
        });
        //optional
        unset($data['links']);
        unset($data['meta']);
        return response()->json($data);
    }
}
