<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
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
    public function scopeSearch($query, $name, $min_price, $max_price)
    {
        return $query
            ->when($name, fn($q) =>
                $q->where('name', 'like', "%{$name}%")
            )
            ->when($min_price, fn($q) =>
                $q->where('price', '>=', $min_price)
            )
            ->when($max_price, fn($q) =>
                $q->where('price', '<=', $max_price)
            );
    }
}
