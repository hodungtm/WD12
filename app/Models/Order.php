<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_date',
        'status',
        'payment_status',
        'payment_method',
        'total_amount',
        'note',
    ];

    // Một đơn hàng thuộc về một khách hàng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Một đơn hàng có nhiều sản phẩm (thông qua bảng trung gian order_items)
    public function orderItems()
    {
        return $this->hasMany(Order_items::class);
    }

    // Tổng tiền tự động tính lại (nếu cần)
    public function calculateTotal()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->total_price;
        });
    }
}
