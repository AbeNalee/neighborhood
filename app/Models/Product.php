<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'sell_price', 'alcoholic'
    ];

    public $incrementing = true;

    /*
     * Attributes
     */
    public function getStockCountAttribute()
    {
        return $this->stocks()->sum('stock_count');
    }

    public function getIsInStockAttribute()
    {
        return $this->stock_count > 0;
    }

    public function getProfitMarginAttribute()
    {
        return $this->sell_price - $this->stocks()->latest()->first()->buy_price;
    }

    public function getValueAttribute()
    {
        return $this->sell_price * $this->stock_count;
    }

    public function getExpenditureAttribute()
    {
        return $this->stocks->sum('value');
    }

    /*
     * Queries
     */
    public function scopeLowStock($query)
    {
        return $query->get()->where('stock_count', '<', 5);
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

    public function CartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function stocks()
    {
        return $this->hasMany(StockControl::class);
    }
}
