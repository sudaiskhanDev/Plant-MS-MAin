<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $table = 'maintenance';

    protected $primaryKey = 'maintenance_id';

    protected $fillable = [
        'plant_id',
        'task_type',
        'scheduled_date',
        'status',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id', 'plant_id');
    }
}