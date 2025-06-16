<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedOrderItem extends Model
{
    use HasFactory;

    // Tên bảng mà model này tương ứng
    protected $table = 'archived_order_items';

    // Định nghĩa các trường có thể được gán hàng loạt (mass assignable).
    // Đây là cách an toàn hơn so với guarded = []
    protected $fillable = [
        'order_id',
        'product_id',
        'discount_id',
        'product_variant_id', // Thêm trường này
        'quantity',
        'price',
        'discount_price',
        'final_price',
        'total_price',
        'product_name',
        'product_sku',
        'size_name', // Thêm trường này
        'color_name', // Thêm trường này
        'created_at',
        'updated_at',
        'archived_at',
    ];

    // Các trường ngày tháng sẽ tự động được chuyển đổi thành Carbon instances
    protected $dates = [
        'created_at',
        'updated_at',
        'archived_at',
    ];

    // Nếu bạn không muốn Laravel tự động quản lý created_at và updated_at cho bảng này
    // (ví dụ: bạn đang sao chép từ bảng gốc có sẵn created_at/updated_at),
    // bạn có thể tắt timestamp:
    // public $timestamps = false; // Mặc định là true, không cần tắt nếu bạn sao chép y nguyên created_at/updated_at

    /**
     * Định nghĩa mối quan hệ với Order (nếu cần truy vấn ngược từ archived_order_item đến Order).
     * Lưu ý: Mối quan hệ này có thể không cần thiết nếu bạn chỉ muốn lưu trữ lịch sử
     * và không bao giờ truy vấn Order từ ArchivedOrderItem.
     * Hoặc bạn có thể tạo mối quan hệ này nhưng không dùng foreign key trong DB.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Các mối quan hệ này chỉ mang tính tham chiếu lịch sử, không nên dùng foreign key trong DB
    // vì product, size, color có thể bị xóa.
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

     public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}