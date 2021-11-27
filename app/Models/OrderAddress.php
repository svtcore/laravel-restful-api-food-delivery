<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;

    public function orders() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function order_cities() {
        return $this->belongsTo(OrderCity::class, 'city_id');
    }

    public function order_street_type() {
        return $this->belongsTo(OrderStreetType::class, 'street_type_id');
    }
}
