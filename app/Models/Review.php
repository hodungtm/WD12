<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'ma_nguoi_dung',
        'ma_san_pham',
        'so_sao',
        'noi_dung',
        'trang_thai',
    ];

    // Quan hệ (sau này)
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'ma_nguoi_dung');
    // }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'ma_san_pham');
    // }
}
