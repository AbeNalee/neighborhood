<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'creditor_id', 'cart_id', 'status', 'pending_amount'
    ];

    public function creditor()
    {
        return $this->belongsTo(Creditor::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function GetPaidAmountAttribute()
    {
        return $this->cart->value - $this->pending_amount;
    }
}
