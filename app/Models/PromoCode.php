<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = ['code', 'type', 'value', 'valid_from', 'valid_to'];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function isValid()
    {
        $now = now();
        return $now->between($this->valid_from, $this->valid_to);
    }
}
