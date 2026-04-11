<?php
namespace App\Services;

use App\DTOs\StockTransferData;
use App\Repositories\StockRepository;
use App\Repositories\StockTransferRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockTransferService
{
    public function __construct(
        private StockRepository $stockRepo,
        private StockTransferRepository $transferRepo
    ) {}

    public function transfer(StockTransferData $dto)
    {
        DB::beginTransaction();
        try {
            $fromStock = $this->stockRepo->getStock(
                $dto->fromWarehouseId,
                $dto->inventoryItemId
            );

            if (! $fromStock || $fromStock->quantity < $dto->quantity) {
                DB::rollBack();
                return ['status' => 'error', 'message' => 'Insufficient stock'];
                // throw new Exception("Insufficient stock");
            }

            $this->stockRepo->decrease($fromStock, $dto->quantity);

            $toStock = $this->stockRepo->increase(
                $dto->toWarehouseId,
                $dto->inventoryItemId,
                $dto->quantity
            );

            $transfer = $this->transferRepo->create([
                'from_warehouse_id' => $dto->fromWarehouseId,
                'to_warehouse_id'   => $dto->toWarehouseId,
                'inventory_item_id' => $dto->inventoryItemId,
                'quantity'          => $dto->quantity,
            ]);
            // 2. إذا وصلنا هنا بدون أخطاء، نعتمد التغييرات نهائياً
            DB::commit();

            // إطلاق الحدث بعد النجاح
            if ($fromStock->fresh()->quantity < 10) {
                event(new \App\Events\LowStockDetected($fromStock));
            }

            return ['status' => 'success', 'message' => 'Stock transferred successfully'];

        } catch (\Exception $e) {
            // 3. في حالة حدوث أي خطأ، نتراجع عن كل شيء وكأن شيئاً لم يكن
            DB::rollBack();

            // تسجيل الخطأ للمتابعة
            Log::error("Transfer failed: " . $e->getMessage());
            return ['status' => 'error', 'message' => "Transfer failed: " . $e->getMessage()];
        }

    }
}
