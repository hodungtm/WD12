<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'admin_id', 'action', 'target_type', 'target_id', 'description', 'ip_address'
    ];

    public function admin()
    {
         return $this->belongsTo(Admin::class, 'admin_id');
    }
}

