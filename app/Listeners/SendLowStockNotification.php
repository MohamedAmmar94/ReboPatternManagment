<?php
namespace App\Listeners;

use App\Events\LowStockDetected;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLowStockNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LowStockDetected $event): void
    {
        \Log::info('Low stock detected', [
            'item_id'  => $event->stock->inventory_item_id,
            'quantity' => $event->stock->quantity,
        ]);
    }
}
