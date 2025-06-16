<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Receiver extends Model
{
    protected $fillable = [
        'user_id', 'name', 'phone', 'email', 'address', 'note'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}