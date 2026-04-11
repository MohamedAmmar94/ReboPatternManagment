<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\InventoryItemController;
use App\Http\Controllers\Api\V1\StockController;
use App\Http\Controllers\Api\V1\StockTransferController;
use App\Http\Controllers\Api\V1\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1', 'middleware' => 'throttle:60,1'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/warehouses', [WarehouseController::class, 'list']);
        Route::post('/warehouse', [WarehouseController::class, 'store']);
        Route::get('/inventory-items', [InventoryItemController::class, 'list_with_filters']);
        Route::post('/inventory-item', [InventoryItemController::class, 'store']);
        Route::get('/stocks', [StockController::class, 'list']);
        Route::post('/stock', [StockController::class, 'store']);
        Route::get('/inventory', [StockController::class, 'inventory']);
        Route::post('/stock_transfers', [StockTransferController::class, 'stock_transfers']);
        Route::get('/warehouses/{id}/inventory', [WarehouseController::class, 'get_warehouse_inventory']);
    });
});
