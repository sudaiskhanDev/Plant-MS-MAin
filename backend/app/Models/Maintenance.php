<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $primaryKey = 'maintenance_id';

    protected $fillable = [
    'plant_id',
    'admin_staff_id', // 🔥 REQUIRED (YOU MISSED THIS)
    'task_type',
    'scheduled_date',
    'status'
];

    public function plant()
{
    return $this->belongsTo(Plant::class, 'plant_id', 'plant_id');
}

public function staff()
{
    return $this->belongsTo(AdminStaff::class, 'admin_staff_id', 'admin_staff_id');
}
}