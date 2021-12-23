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
    public function __construct()
    {
        $this->orders = new Orders();
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $restaurant_id
     * @return \Illuminate\Http\Response
     */
    public function index($restaurant_id){
        $result = $this->orders->getByRestaurantId($restaurant_id, Auth::user()->id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'RECORD_NOT_FOUND');
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
        $validation = Validator::make($request->all(), (new StoreRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('ORDER', 'ORDER_STORE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $order = $this->orders->addByAdmin($request, Auth::user()->id);
            if (isset($order))
                return $this->sendResponse('ORDER', $order);
            else
                return $this->sendError('ORDER', 'FAILED_ADD_ORDER');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $restaurant_id
     * @param int $order_id
     * @return \Illuminate\Http\Response
     */
    public function show($restaurant_id, $order_id)
    {
        $result = $this->orders->getById($order_id, $restaurant_id, Auth::user()->id);
        if (isset($result->id))
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'RECORD_NOT_FOUND');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $restaurant_id
     * @param int $order_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $restaurant_id, $order_id)
    {
        $request->query->set('restaurant_id', $restaurant_id);
        $request->query->set('order_id', $order_id);
        $validation = Validator::make($request->all(), (new UpdateRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('ORDER', 'ORDER_UPDATE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $order = $this->orders->update($request, Auth::user()->id);
            if (isset($order))
                return $this->sendResponse('ORDER', $order);
            else
                return $this->sendError('ORDER', 'FAILED_UPDATE_ORDER');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $restaurant_id
     * @param int $order_id
     * @return \Illuminate\Http\Response
     */
    public function updateByStatus(Request $request, $restaurant_id, $order_id)
    {
        $request->query->set('restaurant_id', $restaurant_id);
        $request->query->set('order_id', $order_id);
        $validation = Validator::make($request->all(), (new StatusUpdateRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('ORDER', 'ORDER_UPDATE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $order = $this->orders->updateStatus($request, Auth::user()->id);
            if (isset($order))
                return $this->sendResponse('ORDER', $order);
            else
                return $this->sendError('ORDER', 'FAILED_UPDATE_ORDER');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $restaurant_id
     * @param int $order_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($restaurant_id, $order_id)
    {
        $result = $this->orders->delete($order_id, $restaurant_id, Auth::user()->id);
        if ($result)
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'FAILED_DELETE');
    }

    /**
     * Display the specified resource.
     *
     * @param int $restaurant_id
     * @param int $status_id
     * @return \Illuminate\Http\Response
     */
    public function showByStatus($restaurant_id, $status_id)
    {
        $result = $this->orders->getByStatusId($status_id, $restaurant_id, Auth::user()->id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'RECORD_NOT_FOUND');
    }

    
}
