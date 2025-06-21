<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
<<<<<<< HEAD
        'size_id',
        'color_id',
        'price',
        'quantity',
=======
        'sku',
        'price',
        'sale_price',
        'quantity',
        'stock_status',
        'description',
        'attribute_text',
        'image',
>>>>>>> main
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
<<<<<<< HEAD

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
=======
>>>>>>> main

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product_variant');
    }
}
