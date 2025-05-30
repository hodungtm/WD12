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
        'foot_length',
        'chest_size',
        'waist_size',
        'hip_size',
    ];

    // Quan hệ với product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
