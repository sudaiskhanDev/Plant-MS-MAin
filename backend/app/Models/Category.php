<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name'
    ];
    public function plants()
{
    return $this->hasMany(Plant::class, 'category_id', 'category_id');
}
}