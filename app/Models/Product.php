<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function order_products() {
        return $this->hasMany(OrderProduct::class);
    }

    public function product_categories() {
        return $this->belongsTo(OrderCategory::class, 'category_id');
    }

    public function restaurants() {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
