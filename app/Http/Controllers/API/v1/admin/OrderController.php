<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\api\v1\BaseController;
use Illuminate\Http\Request;
use App\Http\Classes\Orders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\api\v1\admin\orders\StoreRequest;
use App\Http\Requests\api\v1\admin\orders\UpdateRequest;
use App\Http\Requests\api\v1\admin\orders\StatusUpdateRequest;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->orders = new Orders();
    }

    public function index(Request $request){
        $restaurant_id = intval($request->route('id_rest'));
        $result = $this->orders->getByRestaurantId($restaurant_id, Auth::user()->id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'RECORD_NOT_FOUND');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $validation = Validator::make($request->all(), (new StoreRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('ORDER', 'ORDER_STORE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $order = $this->orders->addByAdmin($request, $restaurant_id, Auth::user()->id);
            if (isset($order))
                return $this->sendResponse('ORDER', $order);
            else
                return $this->sendError('ORDER', 'FAILED_ADD_ORDER');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $order_id = intval($request->route('id'));
        $result = $this->orders->getById($order_id, $restaurant_id, Auth::user()->id);
        if (isset($result->id))
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'RECORD_NOT_FOUND');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $order_id = intval($request->route('id'));
        $validation = Validator::make($request->all(), (new UpdateRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('ORDER', 'ORDER_UPDATE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $order = $this->orders->update($request, $order_id, $restaurant_id, Auth::user()->id);
            if (isset($order))
                return $this->sendResponse('ORDER', $order);
            else
                return $this->sendError('ORDER', 'FAILED_UPDATE_ORDER');
        }
    }

    public function updateByStatus(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $order_id = intval($request->route('id'));
        $validation = Validator::make($request->all(), (new StatusUpdateRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('ORDER', 'ORDER_UPDATE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $order = $this->orders->updateStatus($request, $order_id, $restaurant_id, Auth::user()->id);
            if (isset($order))
                return $this->sendResponse('ORDER', $order);
            else
                return $this->sendError('ORDER', 'FAILED_UPDATE_ORDER');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $order_id = intval($request->route('id'));
        $result = $this->orders->delete($order_id, $restaurant_id, Auth::user()->id);
        if ($result)
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'FAILED_DELETE');
    }

    public function showByStatus(Request $request)
    {
        $restaurant_id = intval($request->route('id_rest'));
        $status_id = intval($request->route('id'));
        $result = $this->orders->getByStatusId($status_id, $restaurant_id, Auth::user()->id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'RECORD_NOT_FOUND');
    }

    
}
