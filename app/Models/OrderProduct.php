<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'order_id',
        'product_id',
    ];

    protected $fillable = [
        'order_id',
        'product_id',
        'amount',
    ];

    public function orders() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }


}
