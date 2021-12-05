<?php

namespace App\Http\Classes;

use App\Models\DeliveryType;
use Exception;
use App\Http\Classes\Restaurants;
use App\Http\Traits\ResultDataTrait;

class DeliveryTypes
{
    use ResultDataTrait;
    /**
     * Initialization object to use restaurants class methods
     */
    public function __construct()
    {
        $this->restaurants = new Restaurants();
    }

    /**
     * Input: restaurant id
     * Output: collection of delivery types or NULL
     * Description: Getting collection of delivery types
     */
    public function getByRestaurantId(int $id): ?iterable
    {
        try {
            $delivery_types = DeliveryType::where('restaurant_id', $id)->get();
            if ($this->check_result($delivery_types))
                return $delivery_types;
            else
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: delivery type id
     * Output: collection or null
     * Description: Getting object of delivery type by id
     */
    public function getById(int $id): ?object
    {
        try {
            $delivery_type = DeliveryType::where('id', $id)->first();
            if ($this->check_result($delivery_type))
                return $delivery_type;
            else
                return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: request data, restaurand id, user id
     * Output: array or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true then add data of delivery type
     */
    public function add(object $request, int $restaurant_id, int $user_id): ?array
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $delivery_type = DeliveryType::create([
                    'restaurant_id' => $restaurant_id,
                    'name' => $request->name,
                    'price' => $request->price,
                    'available' => $request->available,
                ]);
                if (isset($delivery_type->id))
                    return ['delivery_type_id' => $delivery_type->id];
                else return NULL;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: request data, delivery type id, restaurant id, user id
     * Output: boolean or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true then update delivery type data
     */
    public function update(object $request, int $id, int $restaurant_id, int $user_id): ?bool
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $update_result = DeliveryType::where('id', $id)->update(
                    [
                        'restaurant_id' => $restaurant_id,
                        'name' => $request->name,
                        'price' => floatval($request->price),
                        'available' => intval($request->available),
                    ]
                );
                if ($update_result) return true;
                else return false;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: delivery type id, restaurant id, user id
     * Output: boolean or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true then add delete record
     */
    public function delete(int $id, int $restaurant_id, int $user_id): ?bool
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $delivery_type = DeliveryType::findOrFail($id);
                $delete_result = $delivery_type->delete();
                if ($delete_result) return true;
                else return false;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
