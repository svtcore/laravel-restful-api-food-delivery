<?php

namespace App\Http\Classes;

use App\Models\Discount;
use Illuminate\Support\Facades\Route;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Restaurant;
use App\Models\OrderCity;
use App\Models\OrderStreetType;
use App\Models\User;
use Exception;
use App\Http\Classes\Restaurants;

class Orders
{

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

    public function check_result($result)
    {
        if (json_encode($result) == "null")
            return 0;
        else
            return 1;
    }

    public function getByStatusId($status_id, $restaurant_id, $user_id){
        if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
            $orders = Order::with(['order_products.product', 'order_products.product.product_category', 'order_status'])->whereHas('order_products.product', function ($q) use ($restaurant_id) {
                $q->where('restaurant_id', $restaurant_id);
            })->where('status_id', $status_id)->get();
            if ($this->check_result($orders))
                return $orders;
            else
                return 0;
        }
    }

    public function getByRestaurantId($restaurant_id, $user_id)
    {
        if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
            $orders = Order::with(['order_products.product', 'order_products.product.product_category','order_status'])->whereHas('order_products.product', function ($q) use ($restaurant_id) {
                $q->where('restaurant_id', $restaurant_id);
            })->get();
            if ($this->check_result($orders))
                return $orders;
            else
                return 0;
        } else return 0;
    }

    public function getPersonal($user_id)
    {
        $orders = Order::with(
            'order_address',
            'order_address.order_city',
            'order_address.order_street_type',
            'order_status',
            'discount',
            'payment_method',
            'order_products',
            'order_products.product',
        )->where('user_id', $user_id)->get();
        if ($this->check_result($orders))
            return $orders;
        else
            return 0;
    }

    public function getById($id, $restaurant_id, $user_id)
    {
        if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
            $order = Order::with(
                'order_address',
                'order_address.order_city',
                'order_address.order_street_type',
                'order_status',
                'discount',
                'payment_method',
                'order_products',
                'order_products.product'
            )
                ->where('id', $id)
                ->whereHas('order_products.product', function ($q) use ($restaurant_id) {
                    $q->where('restaurant_id', $restaurant_id);
                })->first();
            if ($this->check_result($order))
                return $order;
            else
                return 0;
        } else return 0;
    }

    public function getByIdUserId($id, $user_id)
    {
        $order = Order::with(
            'order_address',
            'order_address.order_city',
            'order_address.order_street_type',
            'order_status',
            'discount',
            'payment_method',
            'order_products',
            'order_products.product'
        )
            ->where('id', $id)
            ->where('user_id', $user_id)->first();
        if ($this->check_result($order))
            return $order;
        else
            return 0;
    }



    public function calculate_cost($order_id, $discount_id)
    {
        $total_cost = 0;
        $products = OrderProduct::with('product')->where('order_id', $order_id)->get();
        for ($i = 0; $i < count($products); $i++) {
            $amount = $products[$i]['amount'];
            $price = $products[$i]['product']['price'];
            $total_cost = $total_cost + ($amount * $price);
        }
        if ($discount_id == NULL)
            return $total_cost;
        else {
            $discount = Discount::where('id', $discount_id)->first();
            if ($discount->value > 0) {
                $discount_value = ($total_cost / 100) * $discount->value;
                $total_cost = $total_cost - $discount_value;
                return $total_cost;
            }
            return $total_cost;
        }
    }


    public function addByAdmin($request, $restaurant_id, $user_id)
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $user = new Users();
                $user_data = $user->getByPhoneNumber($request->phone_number);
                if ($user_data != NULL)
                    $user_id = $user_data->id;
                else {
                    $user_data = $user->addByAdmin($request->first_name, $request->phone_country_code, $request->phone_number);
                    if (isset($user_data->id))
                        $user_id = $user_data->id;
                    else return 0;
                }
                $order = Order::create([
                    'user_id' => $user_id,
                    'payment_type_id' => $request->payment_type_id,
                    'discount_id' => $request->discount_id,
                    'status_id' => 1,
                    'total_cost' => NULL,
                    'comment' => $request->comment,
                ]);
                $order->order_address()->create([
                    'city_id' => $request->city_id,
                    'street_type_id' => $request->street_type_id,
                    'street_name' => $request->street_name,
                    'building_number' => $request->building_number,
                    'entrace' => $request->entrace,
                    'access_code' => $request->access_code,
                    'floor' => $request->floor,
                    'apartment' => $request->apartment,
                ]);
                $products = (array)json_decode($request->products);
                foreach ($products as $key => $value) {
                    $order->order_products()->create([
                        'product_id' => $key,
                        'amount' => $value,
                    ]);
                }
                $total_cost = $this->calculate_cost($order->id, $request->discount);
                $order->update([
                    'total_cost' => $total_cost,
                ]);
                return ['order_id' => $order->id];
            } else return 0;
        } catch (Exception $e) {
            print($e);
        }
    }

    public function updateStatus($request, $order_id, $restaurant_id, $user_id){
        if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
            $order = Order::findOrFail($order_id);
            $order_data = $order->update([
                'status_id' => $request->status_id,
            ]);
            if ($order_data != NULL)
                return $order_data;
            else return 0;
        }
    }

    public function update($request, $order_id, $restaurant_id, $user_id)
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $user = new Users();
                $user_data = $user->getByPhoneNumber($request->phone_number);
                if ($user_data != NULL)
                    $user_id = $user_data->id;
                else {
                    $user_data = $user->addByAdmin($request->first_name, $request->phone_country_code, $request->phone_number);
                    if (isset($user_data->id))
                        $user_id = $user_data->id;
                    else return 0;
                }
                $order = Order::findOrFail($order_id);
                $order_upd = $order->update([
                    'user_id' => $user_id,
                    'payment_type_id' => $request->payment_type_id,
                    'discount_id' => $request->discount_id,
                    'status_id' => $request->status_id,
                    'total_cost' => NULL,
                    'comment' => $request->comment,
                ]);
                $order->order_address()->update([
                    'city_id' => $request->city_id,
                    'street_type_id' => $request->street_type_id,
                    'street_name' => $request->street_name,
                    'building_number' => $request->building_number,
                    'entrace' => $request->entrace,
                    'access_code' => $request->access_code,
                    'floor' => $request->floor,
                    'apartment' => $request->apartment,
                ]);
                $products = (array)json_decode($request->products);
                $order->order_products()->delete();
                foreach ($products as $key => $value) {
                    $order->order_products()->create([
                        'product_id' => $key,
                        'amount' => $value,
                    ]);
                }
                $total_cost = $this->calculate_cost($order->id, $request->discount);
                $order->update([
                    'total_cost' => $total_cost,
                ]);
                return ['updated' => $order_upd];
            } else return 0;
        } catch (Exception $e) {
            print($e);
        }
    }

    public function delete($order_id, $restaurant_id, $user_id)
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $order = Order::findOrFail($order_id);
                $order->order_products()->delete();
                $order->order_address()->delete();
                $order->delete();
                return 1;
            } else return 0;
        } catch (Exception $e) {
            print($e);
        }
    }

    public function add($request, $user_id)
    {
        try {
            $order = Order::create([
                'user_id' => $user_id,
                'payment_type_id' => $request->payment_type_id,
                'discount_id' => $request->discount_id,
                'status_id' => 1,
                'total_cost' => NULL,
                'comment' => $request->comment,
            ]);
            $order->order_address()->create([
                'city_id' => $request->city_id,
                'street_type_id' => $request->street_type_id,
                'street_name' => $request->street_name,
                'building_number' => $request->building_number,
                'entrace' => $request->entrace,
                'access_code' => $request->access_code,
                'floor' => $request->floor,
                'apartment' => $request->apartment,
            ]);
            $products = (array)json_decode($request->products);
            foreach ($products as $key => $value) {
                $order->order_products()->create([
                    'product_id' => $key,
                    'amount' => $value,
                ]);
            }
            $total_cost = $this->calculate_cost($order->id, $request->discount);
            $order->update([
                'total_cost' => $total_cost,
            ]);
            return ['order_id' => $order->id];
        } catch (Exception $e) {
            print($e);
        }
    }

    public function getCities()
    {
        $order_cities = OrderCity::all();
        if ($this->check_result($order_cities))
            return $order_cities;
        else
            return 0;
    }

    public function getStreetTypes()
    {
        $order_street_types = OrderStreetType::all();
        if ($this->check_result($order_street_types))
            return $order_street_types;
        else
            return 0;
    }
}
