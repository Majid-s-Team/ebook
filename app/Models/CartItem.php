<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'book_id', 'quantity', 'price'];
    protected $appends = ['original_price', 'discounted_price'];

    public function getOriginalPriceAttribute()
    {
        return $this->book ? $this->book->price : $this->price;
    }

    public function getDiscountedPriceAttribute()
    {
        return $this->book ? $this->book->discounted_price : $this->price;
    }


    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
