<?php

namespace Database\Seeders;

use App\Models\DeliveryType;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\PaymentMethod;
use App\Models\Discount;
use App\Models\OrderCity;
use App\Models\Product;
use App\Models\OrderStatus;
use App\Models\OrderStreetType;
use App\Models\ProductCategory;
use App\Models\RestaurantCity;
use App\Models\RestaurantStreetType;
use App\Models\Restaurant;
use App\Models\RestaurantAddress;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderAddress;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $config = [
            'discount' => 5,
            'payment_method' => 5,
            'order_status' => 10,
            'order_city' => 10,
            'order_street_type' => 10,
            'product_category' => 10,
            'restaurant_city' => 10,
            'restaurant_street_type' => 10, 
            'restaurant' => 10,
            'delivery_type' => 3,
            'product' => 100,
            'order' => 100,
            'user' => 30,
        ];

        $admin_role = Role::factory()->create(['name' => 'Administrator', 'slug' => 'admin']);
        $user_role = Role::factory()->create(['name' => 'User', 'slug' => 'user']);
        PaymentMethod::factory($config['payment_method'])->create();
        Discount::factory($config['discount'])->create();
        OrderStatus::factory($config['order_status'])->create();
        OrderCity::factory($config['order_city'])->create();
        OrderStreetType::factory($config['order_street_type'])->create();
        ProductCategory::factory($config['product_category'])->create();
        RestaurantCity::factory($config['restaurant_city'])->create();
        RestaurantStreetType::factory($config['restaurant_street_type'])->create();
        Restaurant::factory($config['restaurant'])->create();
        for ($i = 1; $i <= $config['restaurant']; $i++){
            for ($j = 1; $j <=3; $j++){
                RestaurantAddress::factory()->create([
                    'restaurant_id' => $i,
                    'city_id' => rand(1, $config['restaurant_city']),
                    'street_type_id' => rand(1, $config['restaurant_street_type']),
                ]);
            }
            for ($j = 1; $j <= $config['delivery_type']; $j++){
                DeliveryType::factory()->create([
                    'restaurant_id' => $i,
                ]);
            }
        }
        for ($i = 1; $i <= $config['user']; $i++) {
            $user = User::factory()->create();
            $user->createToken('name');
            $array = array($user_role, $admin_role);
            $rand_val = $array[rand(0, count($array) - 1)];
            $user->roles()->attach($rand_val);
            $random_id = rand(1, $config['restaurant']);
            if ($random_id % 2 == 0) $user->restaurants()->attach(Restaurant::find($random_id));
        }
        for ($i = 1; $i <= $config['product']; $i++){
            Product::factory()->create([
                'restaurant_id' => rand(1, $config['restaurant']),
                'category_id' => rand(1, $config['product_category']),
            ]);
        }
        for ($i = 1; $i <= $config['order']; $i++){
            Order::factory()->create([
                'user_id' => rand(1, $config['user']),
                'payment_type_id' => rand(1, $config['payment_method']),
                'discount_id' => rand(1, $config['discount']),
                'status_id' => rand (1, $config['order_status'])
            ]);
            OrderAddress::factory()->create([
                'order_id' => $i,
                'city_id' => rand(1, $config['order_city']),
                'street_type_id' => rand(1, $config['order_street_type']),
            ]);
            OrderProduct::factory()->create([
                'order_id' => $i,
                'product_id' => rand(1, $config['product']),
            ]);
        }
        // \App\Models\User::factory(10)->create();
    }
}
