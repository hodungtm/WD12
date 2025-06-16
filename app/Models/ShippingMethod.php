<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = ['name', 'fee', 'description'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}