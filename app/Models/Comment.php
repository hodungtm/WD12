<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'comments';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'product_id',
        'tac_gia',
        'noi_dung',
        'trang_thai',
    ];


    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
