<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'value', 'purchase_id',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_items')
            ->using(CartItem::class)
            ->withTimestamps();
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeTotalSales($q)
    {
        return $q->where('value', '>', 0)->sum('value');
    }

    public function scopeTotalSpent($q)
    {
        return $q->where('value', '<', 0)->sum('value');
    }
}
