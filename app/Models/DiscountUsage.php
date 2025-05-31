<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountUsage extends Model
{
    protected $fillable = [
        'discount_id',
        'user_id',
        'order_code',
        'used_at',
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
