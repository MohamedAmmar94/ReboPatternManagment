<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes, HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }
    public function inventoryItems()
    {
        return $this->belongsToMany(InventoryItem::class, 'stocks')->withPivot('quantity')->withTimestamps();
    }
}
