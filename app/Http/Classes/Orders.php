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

class Orders
{
    public function check_result($result)
    {
        if (json_encode($result) == "null")
            return 0;
        else
            return 1;
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
            'order_products.product'
        )->where('user_id', $user_id)->get();
        if ($this->check_result($orders))
            return $orders;
        else
            return 0;
    }

    public function getById($id, $user_id)
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
