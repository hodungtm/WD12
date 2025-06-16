<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
   
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
}
