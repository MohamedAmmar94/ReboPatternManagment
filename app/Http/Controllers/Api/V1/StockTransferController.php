<?php
namespace App\Http\Controllers\Api\V1;

use App\DTOs\StockTransferData;
use App\Http\Controllers\Controller;
use App\Services\StockTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StockTransferController extends Controller
{
    public function __construct(private StockTransferService $service)
    {}

    public function stock_transfers(Request $request)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id|different:to_warehouse_id',
            'to_warehouse_id'   => 'required|exists:warehouses,id',
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity'          => 'required|integer|min:1',
        ]);

        $dto           = StockTransferData::fromRequest($request);
        $transfer_data = $this->service->transfer($dto);
        if ($transfer_data['status'] == 'error') {
            return response()->json($transfer_data, 400);
        }
        Cache::tags(["warehouse_{$request->from_warehouse_id}", "warehouse_{$request->to_warehouse_id}"])->flush();
        return response()->json($transfer_data, 200);
    }
}
