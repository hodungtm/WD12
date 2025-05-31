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
        'size',
        'color',
        'quantity',
        'variant_price',
        'variant_sale_price',
    ];

    // Quan hệ với product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
