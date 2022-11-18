<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'buy_price', 'sell_price', 'stock', 'alcoholic'
    ];

    public $incrementing = true;

    /*
     * Attributes
     */
    public function getIsInStockAttribute()
    {
        return $this->stock > 0;
    }

    public function getProfitMarginAttribute()
    {
        return $this->sell_price - $this->buy_price;
    }

    public function getValueAttribute()
    {
        return $this->sell_price * $this->stock;
    }

    /*
     * Queries
     */
    public function scopeLowStock($query)
    {
        return $query->where('stock', '<', 5);
    }

    public function scopeIsInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeIsOldestStock($query)
    {
        return $query->where('stock_date', ''); //todo: get oldest stock for specific name
    }

    /*
     * Relationships
     */
    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_items')
            ->using(CartItem::class)
            ->withTimestamps();
    }
}
