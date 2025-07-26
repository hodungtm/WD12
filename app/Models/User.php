<?php

namespace App\Models;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Wishlist;
use App\Models\Order_items;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';


    protected $fillable = [
        'name', 'email', 'password', 'phone', 'gender',
        'address', 'dob', 'avatar', 'is_active', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $attributes = [
        'role' => self::ROLE_USER,
    ];

    public function isRoleAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function favorites()
    {
        return $this->belongsToMany(Products::class, 'favorites');
    }

    public function orders()
{
    return $this->hasMany(Order::class)->latest();
}

public function wishlist()
{
    return $this->hasMany(Wishlist::class);
}

public function orderItems()
{
    return $this->hasManyThrough(
        Order_items::class,
        \App\Models\Order::class,
        'user_id', // Foreign key on orders table
        'order_id', // Foreign key on order_items table
        'id', // Local key on users table
        'id' // Local key on orders table
    );
}

// public function address()
// {
//     return $this->hasOne(Address::class);
// }



}

