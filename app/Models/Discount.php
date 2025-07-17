<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    // Nếu muốn Laravel tự cast các trường ngày tháng
    protected $dates = ['start_date', 'end_date', 'deleted_at'];

    protected $fillable = [
        'code', 'type', 'description', 'discount_amount', 'discount_percent',
        'start_date', 'end_date', 'max_usage', 'min_order_amount','max_discount_amount'
    ];

    public function usages()
    {
        return $this->hasMany(DiscountUsage::class);
    }
}
