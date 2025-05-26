<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_amount',
        'discount_percent',
        'start_date',
        'end_date',
        'max_usage',
        'min_order_amount',
    ];

    protected $dates = ['start_date', 'end_date'];

    public function usages()
    {
        return $this->hasMany(DiscountUsage::class);
    }
}
