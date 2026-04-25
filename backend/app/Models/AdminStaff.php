<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class AdminStaff extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'admin_staff';

    protected $primaryKey = 'admin_staff_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function maintenances()
{
    return $this->hasMany(Maintenance::class, 'admin_staff_id', 'admin_staff_id');
}
}