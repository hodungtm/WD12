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

        // Snapshot thông tin sản phẩm & biến thể
        'product_name',
        'variant_name',     // "Size M - Màu đỏ"
        'product_image',
        'sku',
        'brand_name',
    ];

    /**
     * Mỗi OrderItem thuộc về một đơn hàng
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    
     public function product()
    {
        return $this->belongsTo(Products::class);
    }

    /**
     * Biến thể sản phẩm liên kết
     */

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    /**
     * Biến thể sản phẩm liên kết
     */

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
