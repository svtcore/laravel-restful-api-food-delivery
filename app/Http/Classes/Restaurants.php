<?php

namespace App\Http\Classes;

use App\Http\Traits\ResultDataTrait;
use App\Models\Restaurant;
use App\Models\RestaurantCity;
use App\Models\User;
use App\Models\RestaurantAddress;
use Exception;

class Restaurants
{
    use ResultDataTrait;

    /**
     * Getting collection of available restaurants
     * 
     * @param null
     * @return Collection|null
     * 
     */
    public function get(): ?iterable
    {
        try {
            $restaurants = Restaurant::with([
                'restaurant_addresses',
                'restaurant_addresses.restaurant_city',
                'restaurant_addresses.restaurant_street_type',
                'delivery_types',
            ])->get();
            if ($this->check_result($restaurants)) return $restaurants;
            else return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Getting collection of restaurants which belong to user (for admin)
     * 
     * @param int $id
     * @return Collection|null
     */
    public function getByUserId(int $id): ?iterable
    {
        try {
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
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Getting object of restaurant by id
     * 
     * @param int $id
     * @return object|null
     * 
     */
    public function getById(int $id): ?object
    {
        try {
            $restaurants = Restaurant::with([
                'restaurant_addresses',
                'restaurant_addresses.restaurant_city',
                'restaurant_addresses.restaurant_street_type',
                'delivery_types',
            ])->where('id', $id)->first();
            if ($this->check_result($restaurants))
                return $restaurants;
            else
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Getting collection of restaurants by product category id
     * 
     * @param int $id
     * @return Collection|null
     * 
     */
    public function getByProductCategoryId(int $id): ?iterable
    {
        try {
            $restaurants = Restaurant::with([
                'restaurant_addresses',
                'restaurant_addresses.restaurant_city',
                'restaurant_addresses.restaurant_street_type',
                'delivery_types'
            ])
                ->whereHas('products', function ($q) use ($id) {
                    $q->where('category_id', $id);
                })->get();
            if ($this->check_result($restaurants))
                return $restaurants;
            else
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Getting collection of restaurants by product id
     * 
     * @param int $id
     * @return Collection|null
     * 
     */
    public function getByProductId(int $id): ?iterable
    {
        try {
            $restaurants = Restaurant::with([
                'restaurant_addresses',
                'restaurant_addresses.restaurant_city',
                'restaurant_addresses.restaurant_street_type',
                'delivery_types'
            ])
                ->whereHas('products', function ($q) use ($id) {
                    $q->where('id', $id);
                })->get();
            if ($this->check_result($restaurants))
                return $restaurants;
            else
                return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Getting collection of restaurants by city id
     * 
     * @param int $id
     * @return Collection|null
     * 
     */
    public function getByCityId(int $id): ?iterable
    {
        try {
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
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Search restaurant by name, description or product name
     * 
     * @param string $query
     * @return Collection|null
     * 
     */
    public function search(string $query): ?iterable
    {
        try {
            $restaurants = Restaurant::with([
                'restaurant_addresses',
                'restaurant_addresses.restaurant_city',
                'restaurant_addresses.restaurant_street_type',
                'delivery_types'
            ])
                ->where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('description', 'LIKE', '%' . $query . '%')
                ->orWhereHas('products', function ($q) use ($query) {
                    $q->where('name', 'LIKE', '%' . $query . '%');
                })->limit(5)->get();
            if ($this->check_result($restaurants))
                return $restaurants;
            else
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Getting collection of cities
     * 
     * @param null
     * @return Collection|null
     * 
     */
    public function getCities(): ?iterable
    {
        try {
            $restaurant_cities = RestaurantCity::all();
            if ($this->check_result($restaurant_cities))
                return $restaurant_cities;
            else
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * adding new restaurant data and assign to manage current user to added restaurant
     * 
     * @param object $request
     * @param int $user_id
     * @return array|null
     * 
     */
    public function add(object $request, int $user_id): ?array
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
            if (isset($restaurant->id))
                return ['restaurant_id' => $restaurant->id];
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Checking if current user has permission to manage data for restaurant.
     * If true update data of restaurant
     * 
     * @param object $request
     * @param int $user_id
     * @return array|null
     * 
     */
    public function update(object $request, int $user_id): ?array
    {
        try {
            if ($this->check_restaurant_access($user_id, intval($request->restaurant_id), intval($request->address_id))) {
                $restaurant = Restaurant::find($request->restaurant_id);
                $restaurant_result = $restaurant->update([
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
                if ($restaurant_result)
                    return ['update' => $restaurant_result];
                else return NULL;
            } else return NULL;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Checking if current user has permission to manage data for restaurant.
     * If true delete records belong it
     * 
     * @param int $id
     * @param int $user_id
     * @return bool|null
     * 
     */
    public function delete(int $id, int $user_id): ?bool
    {
        try {
            if ($this->check_restaurant_access($user_id, intval($id), NULL)) {
                $rest = Restaurant::findOrFail($id);
                $rest->delivery_types()->delete();
                $rest->restaurant_addresses()->delete();
                $restaurant_result = $rest->delete();
                if ($restaurant_result) return true;
                else return NULL;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Checking if current user has permission to manage data for restaurant.
     * If true getting addresses belong to restaurant
     * 
     * @param int $restaurant_id
     * @param int $address_id
     * @param int $user_id
     * @return object|null
     * 
     */
    public function getAddressById(int $restaurant_id, int $address_id, int $user_id): ?object
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, $address_id)) {
                $restaurants = RestaurantAddress::with([
                    'restaurant_city', 'restaurant_street_type'
                ])->where('id', $address_id)->first();
                if ($this->check_result($restaurants))
                    return $restaurants;
                else
                    return NULL;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Checking if current user has permission to manage data for restaurant.
     * If true adding address to restaurant
     * 
     * @param object $request
     * @param int $restaurant_id
     * @param int $user_id
     * @return array|null
     * 
     */
    public function addAddress(object $request, int $user_id): ?array
    {
        try {
            if ($this->check_restaurant_access($user_id, $request->restaurant_id, NULL)) {
                $restaurant = Restaurant::findOrFail(intval($request->restaurant_id));
                $address = $restaurant->restaurant_addresses()->create([
                    'city_id' => $request->city_id,
                    'street_type_id' => $request->street_type_id,
                    'street_name' => $request->street_name,
                    'building_number' => $request->building_number,
                ]);
                if (isset($address->id)) return ['address_id' => $address->id];
                else return NULL;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Checking if current user has permission to manage data for restaurant.
     * If true update address data
     * 
     * @param object $request
     * @param int $user_id
     * @return bool|null
     * 
     */
    public function updateAddress(object $request, int $user_id): ?bool
    {
        try {
            if ($this->check_restaurant_access($user_id, $request->restaurant_id, $request->address_id)) {
                $restaurant = Restaurant::findOrFail(intval($request->restaurant_id));
                $address = $restaurant->restaurant_addresses()->where('id', $request->address_id)->update([
                    'city_id' => $request->city_id,
                    'street_type_id' => $request->street_type_id,
                    'street_name' => $request->street_name,
                    'building_number' => $request->building_number,
                ]);
                if ($address) return true;
                else return false;
            } else return 0;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Checking if current user has permission to manage data for restaurant.
     * If true delete records
     * 
     * @param object $request
     * @param int $user_id
     * @return bool|null
     * 
     */
    public function deleteAddress(object $request, int $user_id): ?bool
    {
        try {
            if ($this->check_restaurant_access($user_id, $request->restaurant_id, $request->address_id)) {
                $rest = RestaurantAddress::findOrFail($request->address_id);
                $restaurant_result = $rest->delete();
                if ($restaurant_result) return true;
                else return false;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Getting collection of delivery types
     * 
     * @param int $id
     * @return Collection|null
     * 
     */
    public function getDeliveryTypes(int $id): ?iterable
    {
        try {
            $delivery_type_obj = new DeliveryTypes();
            $result = $delivery_type_obj->getByRestaurantId($id);
            if ($this->check_result($result))
                return $result;
            else
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Getting restaurant address by id
     * 
     * @param int $id
     * @return Collection|null
     * 
     */
    public function getAddresses(int $id): ?iterable
    {
        try {
            $restaurant_addresses = RestaurantAddress::where('restaurant_id', $id)->get();
            if ($this->check_result($restaurant_addresses))
                return $restaurant_addresses;
            else
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
