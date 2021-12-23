<?php

namespace App\Http\Classes;

use App\Models\DeliveryType;
use Exception;
use App\Http\Classes\Restaurants;
use App\Http\Traits\ResultDataTrait;
use Illuminate\Http\Request;

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
     * Getting collection of delivery types
     * 
     * @param int $id
     * @return Collection|null
     * 
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
     * Getting object of delivery type by id
     * 
     * @param int $id
     * @return object|null
     * 
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
     * Checking if current user has permission to manage data for restaurant.
     * If true then add data of delivery type
     * 
     * @param object $request
     * @param int $user_id
     * @return array|null
     * 
     */
    public function add(object $request, int $user_id): ?array
    {
        try {
            if ($this->check_restaurant_access($user_id, $request->restaurant_id, NULL)) {
                $delivery_type = DeliveryType::create([
                    'restaurant_id' => $request->restaurant_id,
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
     * Checking if current user has permission to manage data for restaurant.
     * If true then update delivery type data
     * 
     * @param object $request
     * @param int $user_id
     * @return bool|null
     * 
     */
    public function update(object $request, int $user_id): ?bool
    {
        try {
            if ($this->check_restaurant_access($user_id, $request->restaurant_id, NULL)) {
                $update_result = DeliveryType::where('id', $request->delivery_type_id)->update(
                    [
                        'restaurant_id' => $request->restaurant_id,
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
     * Checking if current user has permission to manage data for restaurant.
     * If true then add delete record
     * 
     * @param object $request
     * @param int $user_id
     * @return bool|null
     * 
     */
    public function delete(object $request, int $user_id): ?bool
    {
        try {
            if ($this->check_restaurant_access($user_id, $request->restaurant_id, NULL)) {
                $delivery_type = DeliveryType::findOrFail($request->delivery_type_id);
                $delete_result = $delivery_type->delete();
                if ($delete_result) return true;
                else return false;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
