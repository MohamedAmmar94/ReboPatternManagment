<?php
namespace App\Services;

use App\DTOs\InventoryItemData;
use App\Repositories\InventoryRepository;

class InventoryService
{
    public function __construct(private InventoryRepository $repo)
    {}

    public function create(InventoryItemData $dto)
    {
        return $this->repo->create($dto);
    }

    public function list($per_page)
    {
        return $this->repo->list($per_page);
    }
    public function paginateWithFilters($name, $min_price, $max_price, $per_page)
    {
        return $this->repo->paginateWithFilters($name, $min_price, $max_price, $per_page);
    }
}
