<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;  // <--- Thêm dòng này
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'product_id',
        'tac_gia',
        'noi_dung',
        'trang_thai',
    ];

    // Quan hệ với sản phẩm
    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'product_id');
    // }
    
}
