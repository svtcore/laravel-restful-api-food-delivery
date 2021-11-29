<?php

namespace App\Http\Traits;

use App\Models\Restaurant;

trait IsOwnerTrait
{

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'users_restaurants');
    }
}
