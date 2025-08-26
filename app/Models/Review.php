<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
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
    public function user()
    {
        return $this->belongsTo(User::class, 'ma_nguoi_dung', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
}
