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
        'discount_id',
        'quantity',
        'price',  
        'discount_price', // THÊM: Cột này có trong bảng và có thể được gán.
        'final_price',  
        'total_price',
    ];

    /**
     * Mỗi OrderItem thuộc về một đơn hàng
     */
     public function order()
     {
         return $this->belongsTo(Order::class);
     }

    /**
     * Mỗi OrderItem liên kết với một sản phẩm
     */
     public function product()
     {
         return $this->belongsTo(Product::class);
     }

    /**
     * Tính tổng tiền (nếu muốn tính tự động)
     */
    
}
