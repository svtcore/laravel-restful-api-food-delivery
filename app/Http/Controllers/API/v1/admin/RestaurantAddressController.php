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

    /**
     * Display a listing of the resource.
     *
     * @param int $restaurant_id
     * @return \Illuminate\Http\Response
     */
    public function index($restaurant_id)
    {
        $result = $this->restaurants->getAddresses($restaurant_id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('RESTAURANT_ADDRESS', $result);
        else
            return $this->sendError('RESTAURANT_ADDRESS', 'RECORDS_NOT_FOUND');
    }

    /**
     * Display the specified resource.
     *
     * @param int $restaurant_id
     * @param int $address_id
     * @return \Illuminate\Http\Response
     */
    public function show($restaurant_id, $address_id)
    {
        $result = $this->restaurants->getAddressById($restaurant_id, $address_id, Auth::user()->id);
        if (isset($result->id))
            return $this->sendResponse('RESTAURANT_ADDRESS', $result);
        else
            return $this->sendError('RESTAURANT_ADDRESS', 'RECORD_NOT_FOUND');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $restaurant_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $restaurant_id)
    {
        $request->query->set('restaurant_id', $restaurant_id);
        $validation = Validator::make($request->all(), (new AddressStoreRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('RESTAURANT_ADDRESS', 'STORE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $restaurant = $this->restaurants->addAddress($request, Auth::user()->id);
            if (isset($restaurant) && $restaurant != 0)
                return $this->sendResponse('RESTAURANT_ADDRESS', $restaurant);
            else
                return $this->sendError('RESTAURANT_ADDRESS', 'FAILED_ADD_RESTAURANT');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $restaurant_id
     * @param int $address_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $restaurant_id, $address_id)
    {
        $request->query->set('restaurant_id', $restaurant_id);
        $request->query->set('address_id', $address_id);
        $validation = Validator::make($request->all(), (new AddressStoreRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('RESTAURANT_ADDRESS', 'UPDATE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $restaurant = $this->restaurants->updateAddress($request, Auth::user()->id);
            if (isset($restaurant))
                return $this->sendResponse('RESTAURANT_ADDRESS', $restaurant);
            else
                return $this->sendError('RESTAURANT_ADDRESS', 'FAILED_UPDATE_ADDRESS');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $restaurant_id
     * @param int $address_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $restaurant_id, $address_id)
    {
        $request->query->set('restaurant_id', $restaurant_id);
        $request->query->set('address_id', $address_id);
        $result = $this->restaurants->deleteAddress($request, Auth::user()->id);
        if ($result)
            return $this->sendResponse('RESTAURANT_ADDRESS', $result);
        else
            return $this->sendError('RESTAURANT_ADDRESS', 'FAILED_DELETE_ADDRESS');
    }
}
