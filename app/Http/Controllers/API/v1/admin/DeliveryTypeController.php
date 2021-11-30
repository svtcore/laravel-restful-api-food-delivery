<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\api\v1\BaseController;
use Illuminate\Http\Request;
use App\Http\Classes\DeliveryTypes;
use App\Http\Requests\api\v1\admin\delivery_types\StoreRequest;
use App\Http\Requests\api\v1\admin\delivery_types\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeliveryTypeController extends BaseController
{
    public function __construct()
    {
        $this->delivery_types = new DeliveryTypes();
    }

    public function index(Request $request, $id)
    {
        //
    }


    public function show(Request $request){
        $delivery_type_id = intval($request->route('id'));
        $result = $this->delivery_types->getById($delivery_type_id);
        if (isset($result))
            return $this->sendResponse('DELIVERY_TYPE', $result);
        else
            return $this->sendError('DELIVERY_TYPE', 'RECORD_NOT_FOUND');
    }

    public function store(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $validation = Validator::make($request->all(), (new StoreRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('DELIVERY_TYPE', 'STORE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $result = $this->delivery_types->add($request, $restaurant_id, Auth::user()->id);
            if (isset($result) && $result != 0)
                return $this->sendResponse('DELIVERY_TYPE', $result);
            else
                return $this->sendError('DELIVERY_TYPE', 'FAILED_STORE_RESTAURANT');
        }
    }

    public function update(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $delivery_type_id = intval($request->route('id'));
        $validation = Validator::make($request->all(), (new UpdateRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('DELIVERY_TYPE', 'UPDATE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $result = $this->delivery_types->update($request, $delivery_type_id, $restaurant_id, Auth::user()->id);
            if (isset($result) && $result != 0)
                return $this->sendResponse('DELIVERY_TYPE', $result);
            else
                return $this->sendError('DELIVERY_TYPE', 'FAILED_UPDATE_DELIVERY_TYPE');
        }
    }

    public function destroy(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $delivery_type_id = intval($request->route('id'));
        $result = $this->delivery_types->delete(intval($delivery_type_id), intval($restaurant_id), Auth::user()->id);
        if ($result)
            return $this->sendResponse('DELIVERY_TYPE', $result);
        else
            return $this->sendError('DELIVERY_TYPE', 'FAILED_DELETE_DELIVERY_TYPE');
    }
}
