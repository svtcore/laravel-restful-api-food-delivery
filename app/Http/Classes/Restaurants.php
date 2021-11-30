<?php

namespace App\Http\Classes;

use App\Models\Restaurant;
use App\Models\RestaurantCity;
use App\Models\User;
use App\Models\RestaurantAddress;
use Exception;

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

    public function getByProductId($id)
    {
        $restaurants = Restaurant::with('restaurant_addresses', 'restaurant_addresses.restaurant_city', 'restaurant_addresses.restaurant_street_type', 'delivery_types')
            ->whereHas('products', function ($q) use ($id) {
                $q->where('id', $id);
            })->get();
        if ($this->check_result($restaurants))
            return $restaurants;
        else
            return 0;
    }

    public function getByCityId($id)
    {
        $restaurants = Restaurant::with([
            'restaurant_addresses' => function ($q) use ($id) {
                $q->where('city_id', $id);
            },
            'restaurant_addresses.restaurant_city' =>  function ($q) use ($id) {
                $q->where('id', $id);
            },
            'restaurant_addresses.restaurant_street_type', 'delivery_types'
        ])
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

    public function getCities()
    {
        $restaurant_cities = RestaurantCity::all();
        if ($this->check_result($restaurant_cities))
            return $restaurant_cities;
        else
            return 0;
    }


    public function add($request, $user_id)
    {
        try {
            $restaurant = Restaurant::create([
                'name' => $request->name,
                'working_time_start' => $request->working_time_start,
                'working_time_end' => $request->working_time_end,
                'working_day_start' => $request->working_day_start,
                'working_day_end' => $request->working_day_end,
                'description' => $request->description,
            ]);
            $restaurant->restaurant_addresses()->create([
                'city_id' => $request->city_id,
                'street_type_id' => $request->street_type_id,
                'street_name' => $request->street_name,
                'building_number' => $request->building_number,
            ]);
            $user = User::find($user_id);
            $user->restaurants()->attach($restaurant);
            return ['restaurant_id' => $restaurant->id];
        } catch (Exception $e) {
            print($e);
        }
    }

    public function check_restaurant_access($user_id, $restaurant_id, $address_id)
    {
        $restaurants = User::with(
            'restaurants',
            'restaurants.restaurant_addresses',
            'restaurants.restaurant_addresses.restaurant_city',
            'restaurants.restaurant_addresses.restaurant_street_type',
            'restaurants.delivery_types'
        )
            ->where('id', $user_id)->get();
        if ($this->check_result($restaurants)) {
            if (isset($restaurants[0]['restaurants'])) {
                if (count($restaurants[0]['restaurants']) > 0) {
                    for ($i = 0; $i < count($restaurants[0]['restaurants']); $i++) {
                        $rest_id = intval($restaurants[0]['restaurants'][$i]['id']);
                        if ($rest_id == $restaurant_id) {
                            if ($address_id != NULL) {
                                for ($j = 0; $j < count($restaurants[0]['restaurants'][$i]['restaurant_addresses']); $j++) {
                                    $addr_id = intval($restaurants[0]['restaurants'][$i]['restaurant_addresses'][$j]['id']);
                                    if ($addr_id == $address_id) return 1;
                                }
                            } else return 1;
                        }
                    }
                    return 0;
                }
            } else return 0;
        } else return 0;
    }

    public function update($request, $id, $user_id)
    {
        try {
            if ($this->check_restaurant_access($user_id, intval($id), intval($request->address_id))) {
                $restaurant = Restaurant::find($id);
                $restaurant->update([
                    'name' => $request->name,
                    'working_time_start' => $request->working_time_start,
                    'working_time_end' => $request->working_time_end,
                    'working_day_start' => $request->working_day_start,
                    'working_day_end' => $request->working_day_end,
                    'description' => $request->description,
                ]);
                $restaurant->restaurant_addresses()->where('id', $request->address_id)->update([
                    'city_id' => $request->city_id,
                    'street_type_id' => $request->street_type_id,
                    'street_name' => $request->street_name,
                    'building_number' => $request->building_number,
                ]);
                return ['restaurant_id' => $restaurant->id];
            } else return 0;
        } catch (Exception $e) {
            print($e);
        }
    }

    public function delete($id, $user_id)
    {
        if ($this->check_restaurant_access($user_id, intval($id), NULL)) {
            $rest = Restaurant::findOrFail($id);
            $rest->delivery_types()->delete();
            $rest->restaurant_addresses()->delete();
            $rest->delete();
            return 1;
        } else return 0;
    }

    public function getAddressById($restaurant_id, $address_id, $user_id)
    {
        if ($this->check_restaurant_access($user_id, $restaurant_id, $address_id)) {
            $restaurants = RestaurantAddress::with([
                'restaurant_city', 'restaurant_street_type'
            ])->where('id', $address_id)->first();
            if ($this->check_result($restaurants))
                return $restaurants;
            else
                return 0;
        } else return 0;
    }

    public function addAddress($request, $restaurant_id, $user_id)
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $restaurant = Restaurant::findOrFail(intval($restaurant_id));
                $address = $restaurant->restaurant_addresses()->create([
                    'city_id' => $request->city_id,
                    'street_type_id' => $request->street_type_id,
                    'street_name' => $request->street_name,
                    'building_number' => $request->building_number,
                ]);
                return ['address_id' => $address->id];
            } else return 0;
        } catch (Exception $e) {
            print($e);
        }
    }

    public function updateAddress($request, $restaurant_id, $address_id, $user_id)
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, $address_id)) {
                $restaurant = Restaurant::findOrFail(intval($restaurant_id));
                $address = $restaurant->restaurant_addresses()->where('id', $address_id)->update([
                    'city_id' => $request->city_id,
                    'street_type_id' => $request->street_type_id,
                    'street_name' => $request->street_name,
                    'building_number' => $request->building_number,
                ]);
                return $address;
            } else return 0;
        } catch (Exception $e) {
            print($e);
        }
    }

    public function deleteAddress($request, $restaurant_id,  $address_id, $user_id)
    {
        if ($this->check_restaurant_access($user_id, $restaurant_id, $address_id)) {
            $rest = RestaurantAddress::findOrFail($address_id);
            $rest->delete();
            return 1;
        } else return 0;
    }

    public function getDeliveryTypes($id)
    {
        $dl = new DeliveryTypes();
        $result = $dl->getByRestaurantId($id);
        if ($this->check_result($result))
            return $result;
        else
            return 0;
    }
}
