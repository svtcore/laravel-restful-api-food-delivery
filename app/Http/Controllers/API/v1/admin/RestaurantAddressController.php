<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\api\v1\BaseController;
use Illuminate\Http\Request;
use App\Http\Classes\Restaurants;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\api\v1\admin\restaurants\AddressStoreRequest;
use App\Http\Requests\api\v1\admin\restaurants\AddressUpdateRequest;
use App\Http\Requests\api\v1\admin\restaurants\AddressIndexRequest;
use Illuminate\Support\Facades\Validator;

class RestaurantAddressController extends BaseController
{
    public function __construct()
    {
        $this->restaurants = new Restaurants();
    }

    public function index(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $result = $this->restaurants->getAddresses($restaurant_id);
        if (count($result) > 0)
            return $this->sendResponse('RESTAURANT_ADDRESS', $result);
        else
            return $this->sendError('RESTAURANT_ADDRESS', 'RECORDS_NOT_FOUND');
    }

    public function show(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $address_id = intval($request->route('id'));
        $result = $this->restaurants->getAddressById($restaurant_id, $address_id, Auth::user()->id);
        if (isset($result->id))
            return $this->sendResponse('RESTAURANT_ADDRESS', $result);
        else
            return $this->sendError('RESTAURANT_ADDRESS', 'RECORD_NOT_FOUND');
    }

    public function store(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $validation = Validator::make($request->all(), (new AddressStoreRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('RESTAURANT_ADDRESS', 'STORE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $restaurant = $this->restaurants->addAddress($request, $restaurant_id, Auth::user()->id);
            if (isset($restaurant) && $restaurant != 0)
                return $this->sendResponse('RESTAURANT_ADDRESS', $restaurant);
            else
                return $this->sendError('RESTAURANT_ADDRESS', 'FAILED_ADD_RESTAURANT');
        }
    }

    public function update(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $address_id = intval($request->route('id'));
        $validation = Validator::make($request->all(), (new AddressStoreRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('RESTAURANT_ADDRESS', 'UPDATE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $restaurant = $this->restaurants->updateAddress($request, $restaurant_id, $address_id, Auth::user()->id);
            if (isset($restaurant))
                return $this->sendResponse('RESTAURANT_ADDRESS', $restaurant);
            else
                return $this->sendError('RESTAURANT_ADDRESS', 'FAILED_UPDATE_ADDRESS');
        }
    }

    public function destroy(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $address_id = intval($request->route('id'));
        $result = $this->restaurants->deleteAddress($request, $restaurant_id,  $address_id, Auth::user()->id);
        if ($result)
            return $this->sendResponse('RESTAURANT_ADDRESS', $result);
        else
            return $this->sendError('RESTAURANT_ADDRESS', 'FAILED_DELETE_ADDRESS');
    }
}
