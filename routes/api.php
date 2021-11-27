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
      });

    });

    Route::middleware('role:admin')->group(function () {
      Route::prefix('users')->group(function () {
        Route::get('/{id}', 'API\v1\admin\UserController@show');
      });

    });
  });

});
