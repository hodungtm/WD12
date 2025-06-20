<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client\Cart;
class Products extends Model
{
    /** @use HasFactory<\Database\Factories\ProductsFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 
        'price',
        'discount_price', 
        'quantity', 
        'description', // Mô tả sản phẩm
        'category_id', 
        'rating', 
        'size', 
        'color',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
}
