<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment_methods() {
        return $this->belongsTo(PaymentMethod::class, 'payment_type_id');
    }

    public function discounts() {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function order_statuses() {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function order_products() {
        return $this->hasMany(OrderProduct::class);
    }

    public function order_address() {
        return $this->hasOne(OrderAddress::class);
    }
}
