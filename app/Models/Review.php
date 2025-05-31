<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    protected $table = 'reviews';

    protected $fillable = [
        'ma_nguoi_dung',
        'product_id',
        'so_sao',
        'noi_dung',
        'trang_thai',
    ];

    // Quan hệ (sau này)
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'ma_nguoi_dung');
    // }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
