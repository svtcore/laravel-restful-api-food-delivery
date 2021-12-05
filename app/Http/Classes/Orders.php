<?php

namespace App\Http\Classes;

use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderCity;
use App\Models\OrderStreetType;
use Exception;
use App\Http\Traits\ResultDataTrait;

class Orders
{
    use ResultDataTrait;

    /**
     * Input: status id, restaurant id, user id
     * Output: collection of orders
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true getting collection by params: restaurant, status
     */
    public function getByStatusId(int $status_id, int $restaurant_id, int $user_id): ?iterable
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $orders = Order::with([
                    'order_products.product',
                    'order_products.product.product_category',
                    'order_status'
                ])
                    ->whereHas('order_products.product', function ($q) use ($restaurant_id) {
                        $q->where('restaurant_id', $restaurant_id);
                    })->where('status_id', $status_id)->get();
                if ($this->check_result($orders)) return $orders;
                else return NULL;
            }
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: restaurant id, user id
     * Output: collection
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true getting orders by param restaurant id
     */
    public function getByRestaurantId(int $restaurant_id, int $user_id): ?iterable
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $orders = Order::with([
                    'order_products.product',
                    'order_products.product.product_category',
                    'order_status'
                ])->whereHas('order_products.product', function ($q) use ($restaurant_id) {
                    $q->where('restaurant_id', $restaurant_id);
                })->get();
                if ($this->check_result($orders)) return $orders;
                else return NULL;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: user id
     * Output: collection
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true getting orders which belong to current user
     */
    public function getPersonal(int $user_id): ?iterable
    {
        try {
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
            if ($this->check_result($orders)) return $orders;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: order id, restaurant id, user id
     * Output: object
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true getting object of order by id
     */
    public function getById(int $id, int $restaurant_id, int $user_id): ?object
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
            if ($this->check_result($order)) return $order;
            else return NULL;
        } else return NULL;
    }

    /**
     * Input: order id, user id
     * Output: object
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true getting orders by params order id and user id to avoid that another user can not get 
     * order data of another user
     */
    public function getByIdUserId(int $id, int $user_id): ?object
    {
        try {
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
            if ($this->check_result($order)) return $order;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: order id, discount id (or null)
     * Output: mixed
     * Description: Calculation total cost include discount if exist
     */
    public function calculate_cost(int $order_id, ?int $discount_id): mixed
    {
        try {
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
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: request, restaurant id, user id
     * Output: array or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true getting checking if user exist and get his id or if doesn't exist create and get id. 
     * After, adding order data, decode product json and adding their to order
     */
    public function addByAdmin(object $request, int $restaurant_id, int $user_id): ?array
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
                    else return NULL;
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
                $total_cost = $this->calculate_cost($order->id, $request->discount_id);
                $order->update([
                    'total_cost' => $total_cost,
                ]);
                if ($order->id) return ['order_id' => $order->id];
                else return NULL;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: request, order id, restaurant id, user id
     * Output: boolean or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true getting order object and update status
     */
    public function updateStatus(object $request, int $order_id, int $restaurant_id, int $user_id): ?bool
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $order = Order::findOrFail($order_id);
                $order_data = $order->update([
                    'status_id' => $request->status_id,
                ]);
                if ($order_data != NULL) return $order_data;
                else return NULL;
            }
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: request, order id, restaurant id, user id
     * Output: boolean or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true getting checking if user exist and get his id or if doesn't exist create and get id. 
     * After, adding order data, decode product json and adding their to order
     */
    public function update(object $request, int $order_id, int $restaurant_id, int $user_id): ?bool
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
                $total_cost = $this->calculate_cost($order->id, $request->discount_id);
                $order->update([
                    'total_cost' => $total_cost,
                ]);
                if ($order_upd) return true;
                else return NULL;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: order id, restaurant id, user id
     * Output: boolean or null
     * Description: Checking if current user has permission to manage data for restaurant.
     * If true checking order exist then doing cascade delete
     */
    public function delete(int $order_id, int $restaurant_id, int $user_id): ?bool
    {
        try {
            if ($this->check_restaurant_access($user_id, $restaurant_id, NULL)) {
                $order = Order::findOrFail($order_id);
                $order->order_products()->delete();
                $order->order_address()->delete();
                $delete_result = $order->delete();
                if ($delete_result) return true;
                else return false;
            } else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: request, user id
     * Output: array or null
     * Description: adding order data, decode product json and adding their to order, calculate total cost and update data
     */
    public function add(object $request, int $user_id): ?array
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
            if (isset($order->id)) return ['order_id' => $order->id];
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: None
     * Output: collection or null
     * Description: Getting collection of cities available for delivery
     */
    public function getCities(): ?iterable
    {
        try {
            $order_cities = OrderCity::all();
            if ($this->check_result($order_cities)) return $order_cities;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: 
     * Output: collection or null
     * Description: Getting collection of street types
     */
    public function getStreetTypes(): ?iterable
    {
        try {
            $order_street_types = OrderStreetType::all();
            if ($this->check_result($order_street_types)) return $order_street_types;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
