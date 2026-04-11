<?php
namespace App\Repositories;

use App\Models\StockTransfer;

class StockTransferRepository
{
    public function create(array $data)
    {
        return StockTransfer::create($data);
    }
}
