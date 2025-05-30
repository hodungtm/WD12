<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $table = 'product_color'; // vì không theo quy tắc số nhiều

    protected $fillable = [
        'product_id',
        'color_id',
        'image',
    ];
}
