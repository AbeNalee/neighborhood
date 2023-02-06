<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'stock_count', 'buy_price'
    ];

    /*
     * Attributes
     */
    public function getValueAttribute()
    {
        return $this->stock_count * $this->buy_price;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
