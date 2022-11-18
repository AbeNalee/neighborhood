<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'status', 'purpose'
    ];

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    //todo: sales by duration query
}
