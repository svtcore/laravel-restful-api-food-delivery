<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantAddress extends Model
{
    use HasFactory;

    public function restaurants() {
        return $this->belongsTo(Restaurant::class);
    }

    public function restaurant_cities() {
        return $this->belongsTo(RestaurantCity::class, 'city_id');
    }

    public function restaurant_street_types() {
        return $this->belongsTo(RestaurantStreetType::class, 'street_type_id');
    }
}
