<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use SoftDeletes;
   
    protected $table = 'products'; 
    protected $fillable = [
        'name', 'description', 'category_id', 'product_code', 'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
    public function reviews()
{
    return $this->hasMany(Review::class, 'product_id');
}

public function comments()
{
    return $this->hasMany(Comment::class, 'product_id');
}
public function orderItems()
{
    return $this->hasMany(Order_items::class, 'product_id');
}
}
