<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'delivery_address_id', 'promo_code_id', 'total_amount', 'discount', 'status','card_id'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->belongsTo(DeliveryAddress::class, 'delivery_address_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function card(){
        return $this->belongsTo(PaymentCard::class, 'card_id');
    }
}