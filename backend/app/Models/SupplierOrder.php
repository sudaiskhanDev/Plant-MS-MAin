<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
    protected $table = 'supplier_orders';

    protected $primaryKey = 'supplier_order_id';

    protected $fillable = [
        'supplier_id',
        'plant_id',
        'quantity',
        'delivery_status',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id', 'plant_id');
    }
}