<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'created_at',
    ];

    // Liên kết với bảng `users`
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Liên kết với bảng `products`
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
