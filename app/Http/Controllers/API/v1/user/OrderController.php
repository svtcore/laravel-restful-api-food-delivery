<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\API\v1\BaseController;
use Illuminate\Http\Request;
use App\Http\Classes\Orders;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\api\v1\user\orders\StoreRequest;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    public function __construct()
    {
        $this->orders = new Orders();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->orders->getPersonal(Auth::user()->id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'RECORDS_NOT_FOUND');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), (new StoreRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('ORDER', 'ORDER_STORE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $order = $this->orders->add($request, Auth::user()->id);
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
    public function show($id)
    {
        $result = $this->orders->getByIdUserId($id, Auth::user()->id);
        if (isset($result->id))
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'RECORD_NOT_FOUND');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCitiesList()
    {
        $result = $this->orders->getCities();
        if (iterator_count($result) > 0)
            return $this->sendResponse('ORDER_CITY', $result);
        else
            return $this->sendError('ORDER_CITY', 'RECORDS_NOT_FOUND');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showStreetTypesList()
    {
        $result = $this->orders->getStreetTypes();
        if (iterator_count($result) > 0)
            return $this->sendResponse('ORDER_STREET_TYPE', $result);
        else
            return $this->sendError('ORDER_STREET_TYPE', 'RECORDS_NOT_FOUND');
    }
}
