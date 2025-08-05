<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCard extends Model
{
    protected $fillable = [
        'user_id', 'card_number', 'cardholder_name', 'expiry_month', 'expiry_year', 'cvv'
    ];

    protected $hidden = ['card_number', 'cvv'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}