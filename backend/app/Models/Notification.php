<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'admin_staff_id',
        'message',
        'type',
        'date',
    ];

    public function adminStaff()
    {
        return $this->belongsTo(AdminStaff::class, 'admin_staff_id', 'admin_staff_id');
    }
}