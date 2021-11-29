<?php

namespace App\Http\Classes;

use App\Models\Restaurant;
use App\Models\User;

class Restaurants
{

    public function check_result($result)
    {
        if (json_encode($result) == "null")
            return 0;
        else
            return 1;
    }

    public function get()
    {
        $restaurants = Restaurant::with([
            'restaurant_addresses',
            'restaurant_addresses.restaurant_city',
            'restaurant_addresses.restaurant_street_type',
            'delivery_types',
        ])->get();
        if ($this->check_result($restaurants))
            return $restaurants;
        else
            return 0;
    }

    public function getById($id)
    {
        $restaurants = Restaurant::with([
            'restaurant_addresses',
            'restaurant_addresses.restaurant_city',
            'restaurant_addresses.restaurant_street_type',
            'delivery_types',
        ])->where('id', $id)->first();
        if ($this->check_result($restaurants))
            return $restaurants;
        else
            return 0;
    }

    public function getByProductCategoryId($id)
    {
        $restaurants = Restaurant::with('restaurant_addresses', 'restaurant_addresses.restaurant_city', 'restaurant_addresses.restaurant_street_type', 'delivery_types')
            ->whereHas('products', function ($q) use ($id) {
                $q->where('category_id', $id);
            })->get();
        if ($this->check_result($restaurants))
            return $restaurants;
        else
            return 0;
    }

    public function getByCityId($id)
    {
        $restaurants = Restaurant::with('restaurant_addresses', 'restaurant_addresses.restaurant_city', 'restaurant_addresses.restaurant_street_type', 'delivery_types')
            ->whereHas('restaurant_addresses', function ($q) use ($id) {
                $q->where('city_id', $id);
            })->get();
        if ($this->check_result($restaurants))
            return $restaurants;
        else
            return 0;
    }

    public function search($query)
    {
        $restaurants = Restaurant::with('restaurant_addresses', 'restaurant_addresses.restaurant_city', 'restaurant_addresses.restaurant_street_type', 'delivery_types')
            ->where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('description', 'LIKE', '%' . $query . '%')
            ->orWhereHas('products', function ($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%');
            })->limit(5)->get();
        if ($this->check_result($restaurants))
            return $restaurants;
        else
            return 0;
    }

    public function getByUserId($id)
    {
        $restaurants = User::with(
            'restaurants',
            'restaurants.restaurant_addresses',
            'restaurants.restaurant_addresses.restaurant_city',
            'restaurants.restaurant_addresses.restaurant_street_type',
            'restaurants.delivery_types'
        )
            ->where('id', $id)->get()->pluck('restaurants');
        if ($this->check_result($restaurants))
            return $restaurants;
        else
            return 0;
    }
}
