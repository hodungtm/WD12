<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'shipping_method_id',

        // Thông tin người nhận (nếu không dùng bảng receivers)
        'receiver_name',
        'receiver_phone',
        'receiver_email',
        'receiver_address',

        'order_date',
        'note',

        // Trạng thái đơn hàng
        'status',

        // Thanh toán
        'payment_status',
        'payment_method',

        // Giá tiền
        'total_price',
        'discount_amount',
        'final_amount',
        'discount_code',
        'cancel_reason'
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    /** Quan hệ Eloquent **/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

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
