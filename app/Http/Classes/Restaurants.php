<?php

namespace App\Http\Classes;

use App\Models\Restaurant;

class Restaurants
{
    public function get(){
        $restaurants = Restaurant::with([
            'restaurant_addresses',
            'restaurant_addresses.restaurant_cities',
            'restaurant_addresses.restaurant_street_types'])->get();
        return json_decode($restaurants);
    }
}
