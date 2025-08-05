<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'book_category_id',
        'book_name',
        'about',
        'author_name',
        'is_popular',
        'is_audio',
        'is_reader',
        'image_url',
        'audio_url',
        'made_into_movie',
        'price',
        'quantity',
        'discount'
    ];

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    public function chapters()
    {
        return $this->hasMany(BookChapter::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorite_books');
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->discount && $this->discount > 0) {
            return max($this->price - $this->discount, 0);
        }

        return $this->price;
    }

}
