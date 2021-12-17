<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\api\v1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Classes\Restaurants;
use App\Http\Requests\api\v1\admin\restaurants\StoreRequest;
use App\Http\Requests\api\v1\admin\restaurants\UpdateRequest;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends BaseController
{
    public function __construct()
    {
        $this->restaurants = new Restaurants();
    }

    public function index()
    {
        $result = $this->restaurants->getByUserId(Auth::user()->id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('RESTAURANT', $result);
        else
            return $this->sendError('RESTAURANT', 'RECORDS_NOT_FOUND');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), (new StoreRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('RESTAURANT', 'STORE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $order = $this->restaurants->add($request, Auth::user()->id);
            if (isset($order))
                return $this->sendResponse('RESTAURANT', $order);
            else
                return $this->sendError('RESTAURANT', 'FAILED_ADD_RESTAURANT');
        }
    }

    public function show($id)
    {
        $result = $this->restaurants->getById($id);
        if (isset($result->id))
            return $this->sendResponse('RESTAURANT', $result);
        else
            return $this->sendError('RESTAURANT', 'RECORD_NOT_FOUND');
    }

    public function update(Request $request, $id)
    {
        $request->query->set('restaurant_id', $id);
        $validation = Validator::make($request->all(), (new UpdateRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('RESTAURANT', 'UPDATE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $order = $this->restaurants->update($request, Auth::user()->id);
            if (isset($order) && $order != NULL)
                return $this->sendResponse('RESTAURANT', $order);
            else
                return $this->sendError('RESTAURANT', 'FAILED_UPDATE_RESTAURANT');
        }
    }

    public function destroy($id)
    {
        $result = $this->restaurants->delete($id, Auth::user()->id);
        if ($result)
            return $this->sendResponse('RESTAURANT', $result);
        else
            return $this->sendError('RESTAURANT', 'FAILED_DELETE_RESTAURANT');
    }

    public function showDeliveryTypes($id){
        $result = $this->restaurants->getDeliveryTypes($id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('DELIVERY_TYPE', $result);
        else
            return $this->sendError('DELIVERY_TYPE', 'RECORDS_NOT_FOUND');
    }
}
