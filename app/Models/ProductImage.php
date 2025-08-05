<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'image', 'color_id'];

    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
}

