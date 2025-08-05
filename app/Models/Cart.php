<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'subtotal', 'discount', 'delivery_fee', 'total', 'promo_code_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
