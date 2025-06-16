<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_id', // Thêm nếu bạn muốn gán receiver_id qua mass assignment
        'discount_id', // Thêm nếu bạn muốn gán discount_id qua mass assignment
        'order_date',
        'status',
        'payment_status',
        'payment_method',
        'total_amount',
        'note',
        'shipping_method_id', // <-- THÊM DÒNG NÀY
        'order_code', // <-- THÊM DÒNG NÀY nếu bạn cũng tạo order_code qua mass assignment
        'total_price', // <-- THÊM nếu bạn gán nó qua mass assignment
        'discount_amount', // <-- THÊM nếu bạn gán nó qua mass assignment
        'final_amount', 
    ];

    // Một đơn hàng thuộc về một khách hàng
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function receiver()
{
    return $this->belongsTo(Receiver::class);
}

 public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

public function shippingMethod()
{
    return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
}


 public function archivedOrderItems()
    {
        return $this->hasMany(ArchivedOrderItem::class);
    }

    // Một đơn hàng có nhiều sản phẩm (thông qua bảng trung gian order_items)
    public function orderItems()
    {
        return $this->hasMany(Order_items::class);
    }

    // Tổng tiền tự động tính lại (nếu cần)
  public function calculateTotal()
{
    return $this->orderItems->sum('total_price') + ($this->shipping_fee ?? 0);
}
}
