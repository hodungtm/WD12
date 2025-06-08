<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'variant_price',
        'variant_sale_price',
    ];

    // Quan hệ với product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
     public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
}
