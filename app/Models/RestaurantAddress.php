<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantAddress extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'city_id',
        'street_type_id',
        'restaurant_id',
    ];

    protected $fillable = [
        'city_id',
        'street_type_id',
        'street_name',
        'building_number',
    ];


    public function restaurants() {
        return $this->belongsTo(Restaurant::class);
    }

    public function restaurant_city() {
        return $this->belongsTo(RestaurantCity::class, 'city_id');
    }

    public function restaurant_street_type() {
        return $this->belongsTo(RestaurantStreetType::class, 'street_type_id');
    }
}
