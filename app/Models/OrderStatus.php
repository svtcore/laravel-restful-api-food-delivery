<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'order_id',
        'product_id',
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
