<?php
namespace App\Services;

use App\Repositories\StockRepository;

class StockService
{
    public function __construct(private StockRepository $stockRepo)
    {}

    public function setStock($dto)
    {
        return $this->stockRepo->setStock($dto);
    }
    public function paginateWithFilters($inventoryItemId, $warehouseId, $perPage, $filters)
    {
        return $this->stockRepo->paginateWithFilters($inventoryItemId, $warehouseId, $perPage, $filters);
    }
}
