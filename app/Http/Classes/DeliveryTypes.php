<?php

namespace App\Http\Classes;

use App\Models\DeliveryType;
use Exception;
use App\Http\Classes\Restaurants;

class DeliveryTypes
{

    public function __construct()
    {
        $this->restaurants = new Restaurants();
    }

    public function check_result($result)
    {
        if (json_encode($result) == "null")
            return 0;
        else
            return 1;
    }

    public function getByRestaurantId($id)
    {
        $delivery_types = DeliveryType::where('restaurant_id', $id)->get();
        if ($this->check_result($delivery_types))
            return $delivery_types;
        else
            return 0;
    }

    public function getById($id)
    {
        $delivery_type = DeliveryType::where('id', $id)->first();
        if ($this->check_result($delivery_type))
            return $delivery_type;
        else
            return 0;
    }

    public function add($request, $restaurant_id, $user_id)
    {
        try {
            if ($this->restaurants->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $delivery_type = DeliveryType::create([
                    'restaurant_id' => $restaurant_id,
                    'name' => $request->name,
                    'price' => $request->price,
                    'available' => $request->available,
                ]);
                return ['delivery_type_id' => $delivery_type->id];
            } else return 0;
        } catch (Exception $e) {
            print($e);
        }
    }

    public function update($request, $id, $restaurant_id, $user_id)
    {
        try {
            if ($this->restaurants->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $updated = DeliveryType::where('id', $id)->update(
                    [
                        'restaurant_id' => $restaurant_id,
                        'name' => $request->name,
                        'price' => floatval($request->price),
                        'available' => intval($request->available),
                    ]
                );
                return $updated;
            } else return 0;
        } catch (Exception $e) {
            print($e);
        }
    }

    public function delete($id, $restaurant_id, $user_id)
    {
        if ($this->restaurants->check_restaurant_access($user_id, $restaurant_id, NULL)) {
            $delivery_type = DeliveryType::findOrFail($id);
            $delivery_type->delete();
            return 1;
        } else return 0;
    }
}
