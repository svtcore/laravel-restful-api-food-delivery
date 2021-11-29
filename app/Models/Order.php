<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $hidden = [
        'updated_at',
        'user_id',
        'payment_type_id',
        'discount_id',
        'status_id',
    ];

    protected $fillable = [
        'user_id',
        'payment_type_id',
        'discount_id',
        'status_id',
        'total_cost',
        'comment',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment_method() {
        return $this->belongsTo(PaymentMethod::class, 'payment_type_id');
    }

    public function discount() {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function order_status() {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function order_products() {
        return $this->hasMany(OrderProduct::class);
    }

    public function order_address() {
        return $this->hasOne(OrderAddress::class);
    }
}
