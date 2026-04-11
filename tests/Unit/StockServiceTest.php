<?php
namespace Tests\Unit;

use App\DTOs\StockTransferData;
use App\Repositories\StockRepository;
use App\Repositories\StockTransferRepository;
use App\Services\StockTransferService;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
use Mockery;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    public function test_transfer_fails_when_stock_is_insufficient()
    {
        // DB::shouldReceive('beginTransaction')->andReturnNull();
        // DB::shouldReceive('commit')->andReturnNull();
        // DB::shouldReceive('rollBack')->andReturnNull();
        // Log::shouldReceive('error')->once();
        $stockRepo    = Mockery::mock(StockRepository::class);
        $transferRepo = Mockery::mock(StockTransferRepository::class);

        $stockRepo->shouldReceive('getStock')
            ->once()
            ->andReturn((object) [
                'quantity' => 50,
            ]);

        $service = new StockTransferService($stockRepo, $transferRepo);
        $dto     = new StockTransferData(
            fromWarehouseId: 1,
            toWarehouseId: 2,
            inventoryItemId: 10,
            quantity: 100
        );
        $result = $service->transfer($dto);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Insufficient stock', $result['message']);
    }

}
