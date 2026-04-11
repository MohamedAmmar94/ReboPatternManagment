<?php
namespace App\Services;

use App\DTOs\WarehouseData;
use App\Repositories\WarehouseRepository;

class WarehouseService
{
    public function __construct(private WarehouseRepository $repo)
    {}

    public function create(WarehouseData $dto)
    {
        return $this->repo->create($dto);
    }

    public function list()
    {
        return $this->repo->all();
    }
    public function paginate($perPage)
    {
        return $this->repo->paginate($perPage);
    }
    public function get_warehouse_inventories($id, $page, $perPage, $filters)
    {
        return $this->repo->get_warehouse_inventories($id, $page, $perPage, $filters);
    }
}
