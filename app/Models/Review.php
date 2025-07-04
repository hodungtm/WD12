<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'reviews';
    protected $dates = ['deleted_at'];
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
        return $this->belongsTo(Products::class, 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_dung'); // 'user_id' là khóa ngoại trong bảng reviews
    }
}
