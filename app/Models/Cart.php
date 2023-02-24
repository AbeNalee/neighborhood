<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    public function scopeTotalSalesToday($q)
    {
        return $q->where('value', '>', 0)->whereDate('created_at', '>', Carbon::yesterday())->sum('value');
    }

    public function scopeTotalSalesWeek($q)
    {
        return $q->where('value', '>', 0)->whereDate('created_at', '>', Carbon::today()->startOfWeek())->sum('value');
    }

    public function scopeTotalSalesMonth($q)
    {
        return $q->where('value', '>', 0)->whereDate('created_at', '>', Carbon::today()->startOfMonth())->sum('value');
    }

    public function scopeTotalSpent($q)
    {
        return $q->where('value', '<', 0)->sum('value');
    }
}
