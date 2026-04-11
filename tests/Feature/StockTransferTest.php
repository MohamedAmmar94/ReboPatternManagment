<?php
namespace Tests\Feature;

use App\DTOs\StockTransferData;
use App\Models\InventoryItem;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Services\StockTransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTransferTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_stock_transfer_successfully()
    {
        $from = Warehouse::factory()->create();
        $to   = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();
        Stock::create([
            'warehouse_id'      => $from->id,
            'inventory_item_id' => $item->id,
            'quantity'          => 50,
        ]);
        $dto = new StockTransferData(
            fromWarehouseId: $from->id,
            toWarehouseId: $to->id,
            inventoryItemId: $item->id,
            quantity: 20
        );
        $service = app(StockTransferService::class);
        $result  = $service->transfer($dto);
        $this->assertEquals('success', $result['status']);

        $this->assertDatabaseHas('stocks', [
            'warehouse_id'      => $from->id,
            'inventory_item_id' => $item->id,
            'quantity'          => 30,
        ]);
    }

}
