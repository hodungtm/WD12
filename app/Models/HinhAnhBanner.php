<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhAnhBanner extends Model
{
    use HasFactory;

    protected $table = 'hinh_anh_banners';

    protected $fillable = [
        'banner_id',
        'hinh_anh',
    ];


    public function banner(){
        return $this->belongsTo(Banner::class, 'banner_id');
    }
}
