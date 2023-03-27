<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creditor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone',
    ];

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function getAmountOwedAttribute()
    {
        return $this->credits()->sum('pending_amount');
    }

    public function scopeHighMargin()
    {
        //
    }
}
