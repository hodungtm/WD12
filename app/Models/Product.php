<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'image','category_id', 'slug', 'description', 'price', 'quantity', 'brand_id'];

    // Liên kết với Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
     public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Mối quan hệ với Bình luận (nếu có riêng)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function images()
{
    return $this->hasMany(ProductImage::class);
}
}
