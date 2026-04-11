<?php
namespace Tests\Feature;

use App\DTOs\StockTransferData;
use App\Events\LowStockDetected;
use App\Repositories\StockRepository;
use App\Repositories\StockTransferRepository;
use App\Services\StockTransferService;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class LaunchEventTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_low_stock_event_is_dispatched()
    {
        Event::fake();
        $stockRepo           = Mockery::mock(StockRepository::class);
        $transferRepo        = Mockery::mock(StockTransferRepository::class);
        $fromStock           = Mockery::mock();
        $fromStock->quantity = 15;
        $fromStock->shouldReceive('fresh')->andReturn((object) ['quantity' => 5]);
        $stockRepo->shouldReceive('getStock')->andReturn($fromStock);
        $stockRepo->shouldReceive('decrease')->andReturnNull();
        $stockRepo->shouldReceive('increase')->andReturnNull();
        $transferRepo->shouldReceive('create')->andReturn((object) []);

        $service = new StockTransferService($stockRepo, $transferRepo);

        $dto = new StockTransferData(1, 2, 10, 10);

        $service->transfer($dto);

        Event::assertDispatched(LowStockDetected::class);
    }
}
