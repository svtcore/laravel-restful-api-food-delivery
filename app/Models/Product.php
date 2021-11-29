<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'restaurant_id',
        'category_id',
    ];

    public function order_products() {
        return $this->hasMany(OrderProduct::class);
    }

    public function product_category() {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function restaurants() {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
