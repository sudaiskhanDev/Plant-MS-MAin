<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'name',
        'contact',
        'address'
    ];

    public function supplierOrders()
{
    return $this->hasMany(SupplierOrder::class, 'supplier_id', 'supplier_id');
}
}