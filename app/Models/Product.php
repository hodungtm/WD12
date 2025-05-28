<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory; 
    protected $fillable = ['name', 'description', 'price', 'image'];

    // Quan hệ với Wishlist
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
