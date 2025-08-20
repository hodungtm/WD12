<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_items extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'price',
        'total_price',
        'product_name',
        'variant_name',
        'product_image',
        'sku',
        'brand_name',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
    public function variant()  // Changed from productVariant to match the view
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}