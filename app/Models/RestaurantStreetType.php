<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantStreetType extends Model
{
    use HasFactory;

    public function restaurant_addresses() {
        return $this->hasMany(RestaurantAddress::class);
    }
}
