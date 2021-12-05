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
     * Input: None
     * Output: collection or null
     * Description: Getting collection of available restaurants
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
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: restaurant id
     * Output: collection or null
     * Description: Getting collection of restaurants which belong to user (for admin)
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
     * Input: restaurant id
     * Output: object or null
     * Description: Getting object of restaurant by id
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
     * Input: category id
     * Output: collection or null
     * Description: Getting collection of restaurants by product category id
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
     * Input: product id
     * Output: collection or null
     * Description: Getting collection of restaurants by product id
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
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: city id
     * Output: collection or null
     * Description: Getting collection of restaurants by city id
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
     * Input: string query
     * Output: collection or null
     * Description: Search restaurant by name, description or product name
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
     * Input: None
     * Output: collection or null
     * Description: Getting collection of cities
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
     * Input: request, user id
     * Output: array or null
     * Description: adding new restaurant data and assign to manage current user to added restaurant
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
     * Input: request, restaurant id, user id
     * Output: array or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true update data of restaurant
     */
    public function update(object $request, int $id, int $user_id): ?array
    {
        try {
            if ($this->check_restaurant_access($user_id, intval($id), intval($request->address_id))) {
                $restaurant = Restaurant::find($id);
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
     * Input: restaurant id, user id
     * Output: boolean or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true delete records belong it
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
     * Input: restaurant id, address id, user id
     * Output: object or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true getting addresses belong to restaurant
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
     * Input: request, restaurant id, user id
     * Output: array or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true adding address to restaurant
     */
    public function addAddress(object $request, int $restaurant_id, int $user_id): ?array
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
                if (isset($address->id)) return ['address_id' => $address->id];
                else return NULL;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: request, restaurant id, address id, user id
     * Output: boolean or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true update address data
     */
    public function updateAddress(object $request, int $restaurant_id, int $address_id, int $user_id): ?bool
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
                if ($address) return true;
                else return false;
            } else return 0;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: request id, address id, user id
     * Output: boolean or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true delete records
     */
    public function deleteAddress(object $request, int $restaurant_id,  int $address_id, int $user_id): ?bool
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, $address_id)) {
                $rest = RestaurantAddress::findOrFail($address_id);
                $restaurant_result = $rest->delete();
                if ($restaurant_result) return true;
                else return false;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: restaurant id
     * Output: collection or null
     * Description: Getting collection of delivery types
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
     * Input: restaurant id
     * Output: collection or null
     * Description: Getting restaurant address by id
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
