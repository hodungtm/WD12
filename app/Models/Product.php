<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    // Nếu bạn dùng tên bảng mặc định 'products' thì không cần khai báo
    // protected $table = 'products';

    protected $fillable = [
        'name',
        'image_product',
        'type',
        'description',
        'price',
        'sale_price',
        'sku',
        'brand',
        'slug',
        'status',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }




    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            do {
                $randomNumber = mt_rand(100000, 999999); // số 6 chữ số ngẫu nhiên
                $sku = 'SP' . $randomNumber;
            } while (self::where('sku', $sku)->exists()); // kiểm tra trùng

            $product->sku = $sku;
        });
    }

    // client

   

    public function reviews()
    {
        return $this->hasMany(Review::class)->whereNull('deleted_at');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('deleted_at');
    }
}
