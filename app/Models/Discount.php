<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $dates = ['start_date', 'end_date', 'deleted_at'];

    protected $fillable = [
        'code',
        'type',
        'description',
        'discount_amount',
        'discount_percent',
        'start_date',
        'end_date',
        'max_usage',
        'min_order_amount',
        'max_discount_amount',
    ];

    // Hằng số cho các loại giảm giá
    public const TYPE_PERCENT = 'percent';
    public const TYPE_FIXED = 'fixed';
    

    // Trả về danh sách các loại hợp lệ
    public static function getTypes(): array
    {
        return [
            self::TYPE_PERCENT => 'Giảm theo %',
            self::TYPE_FIXED => 'Giảm số tiền cố định',
            
        ];
    }

    // Quan hệ lượt sử dụng mã
    public function usages()
    {
        return $this->hasMany(DiscountUsage::class);
    }

    // Kiểm tra type tiện lợi
    public function isPercent(): bool
    {
        return $this->type === self::TYPE_PERCENT;
    }

    public function isFixed(): bool
    {
        return $this->type === self::TYPE_FIXED;
    }

  
    
}
