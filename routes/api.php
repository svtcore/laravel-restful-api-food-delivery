<?php

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
  Route::post('login', 'API\v1\auth\LoginController@login');
  Route::post('register', 'API\v1\auth\RegisterController@register');

  Route::middleware('auth:api')->group(function () {

    Route::namespace('API\v1\user')->middleware('role:user')->group(function () {

      Route::prefix('restaurants')->group(function () {
        Route::get('/', 'RestaurantController@index')->name('user.restaurant.index');
        Route::get('/search', 'RestaurantController@search')->name('user.restaurant.search');
        Route::get('/{id}', 'RestaurantController@show')->name('user.restaurant.show');
        Route::prefix('categories')->group(function () {
          Route::get('/{id}', 'RestaurantController@showByCategoryId');
        });
        Route::prefix('cities')->group(function () {
          Route::get('/{id}', 'RestaurantController@showByCityId');
        });
      });

      Route::prefix('orders')->group(function () {
        Route::get('/', 'OrderController@index')->name('user.orders.index');
        Route::get('/{id}', 'OrderController@show')->name('user.orders.show');
        Route::post('/', 'OrderController@store')->name('user.orders.store');
      });

      Route::prefix('products')->group(function () {
        Route::get('/', 'ProductController@index')->name('user.products.index');
        Route::get('/{id}', 'ProductController@show')->name('user.products.show');
        Route::prefix('categories')->group(function () {
          Route::get('/{id}', 'ProductController@showByCategoryId')->name('user.products.byCategoryId');
        });
        Route::prefix('restaurants')->group(function () {
          Route::get('/{id}', 'ProductController@showByRestaurantId')->name('user.products.byRestaurantId');
        });
      });

      Route::prefix('payment-methods')->group(function () {
        Route::get('/', 'PaymentMethodController@index')->name('user.payments.index');
        Route::get('/{id}', 'PaymentMethodController@show')->name('user.payments.show');
      });

    });

    Route::middleware('role:admin')->group(function () {
      Route::prefix('users')->group(function () {
        Route::get('/{id}', 'API\v1\admin\UserController@show');
      });

    });
  });

});
