<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantCity extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function restaurant_addresses() {
        return $this->hasMany(RestaurantAddress::class);
    }
}
