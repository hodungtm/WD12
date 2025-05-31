<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
     use SoftDeletes;

    // Mdel muốn thao tác với bảng nào thì sẽ cần quy định ở biến table
    protected $table = 'categories';

    // $fillable: cho phép điền dữ liệu vào các cột tương ứng
    protected $fillable = [
        'ten_danh_muc',
        'trang_thai'
    ];

    // Tạo mối qua hệ với bảng product (1 - N)
    public function product()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
