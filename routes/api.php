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
      Route::prefix('user')->group(function () {
        Route::prefix('restaurants')->group(function () {
          Route::prefix('cities')->group(function () {
            Route::get('/', 'RestaurantController@showCitiesList');
            Route::get('/{id}', 'RestaurantController@showByCityId');
          });
          Route::prefix('categories')->group(function () {
            Route::get('/{id}', 'RestaurantController@showByCategoryId');
          });
          Route::prefix('products')->group(function () {
            Route::get('/{id}', 'RestaurantController@showByProductId');
          });
          Route::get('/', 'RestaurantController@index');
          Route::get('/search', 'RestaurantController@search');
          Route::get('/{id}', 'RestaurantController@show');
        });

        Route::prefix('orders')->group(function () {
          Route::prefix('cities')->group(function () {
            Route::prefix('street-types')->group(function () {
              Route::get('/', 'OrderController@showStreetTypesList');
            });
            Route::get('/', 'OrderController@showCitiesList');
          });
          Route::get('/', 'OrderController@index');
          Route::get('/{id}', 'OrderController@show');
          Route::post('/', 'OrderController@store');
        });

        Route::prefix('products')->group(function () {
          Route::prefix('restaurants')->group(function () {
            Route::get('/{id}', 'ProductController@showByRestaurantId');
          });
          Route::prefix('categories')->group(function () {
            Route::get('/', 'ProductController@showCategoryList');
          });
          Route::get('/', 'ProductController@index');
          Route::get('/{id}', 'ProductController@show');
          Route::prefix('categories')->group(function () {
            Route::get('/{id}', 'ProductController@showByCategoryId');
          });
        });

        Route::prefix('payment-methods')->group(function () {
          Route::get('/', 'PaymentMethodController@index');
          Route::get('/{id}', 'PaymentMethodController@show');
        });
      });
    });

    Route::namespace('API\v1\admin')->middleware('role:admin')->group(function () {
      Route::prefix('admin')->group(function () {
        Route::prefix('restaurants')->group(function () {
          Route::prefix('{id_rest}')->group(function () {
            Route::prefix('delivery-types')->group(function () {
              Route::get('/', 'RestaurantController@showDeliveryTypes');
              Route::get('/{id}', 'DeliveryTypeController@show')->where('id_rest', '[0-9]+');
              Route::post('/', 'DeliveryTypeController@store');
              Route::put('/{id}', 'DeliveryTypeController@update');
              Route::delete('/{id}', 'DeliveryTypeController@destroy');
            });

            Route::prefix('addresses')->group(function () {
              Route::get('/', 'RestaurantAddressController@index');
              Route::get('/{id}', 'RestaurantAddressController@show');
              Route::post('/', 'RestaurantAddressController@store');
              Route::put('/{id}', 'RestaurantAddressController@update');
              Route::delete('/{id}', 'RestaurantAddressController@destroy');
            });

            Route::prefix('orders')->group(function () {
              Route::get('/', 'OrderController@index');
              Route::get('/{id}', 'OrderController@show');
              Route::post('/', 'OrderController@store');
              Route::put('/{id}', 'OrderController@update');
              Route::delete('/{id}', 'OrderController@destroy');
            });
          });
          Route::get('/', 'RestaurantController@index')->name('admin.restaurant.index');
          Route::post('/', 'RestaurantController@store')->name('admin.restaurant.store');
          Route::put('/{id}', 'RestaurantController@update')->name('admin.restaurant.update');
          Route::get('/{id}', 'RestaurantController@show')->name('admin.restaurant.show');
          Route::delete('/{id}', 'RestaurantController@destroy')->name('admin.restaurant.destroy');
        });
      });
    });
  });
});
