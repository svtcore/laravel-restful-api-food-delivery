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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->orders = new Orders();
    }

    public function index()
    {
        $result = $this->orders->getPersonal(Auth::user()->id);
        if (count($result) > 0)
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'RECORDS_NOT_FOUND');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        print($this->orders->add($request, Auth::user()->id));
        /*$validation = Validator::make($request->all(), (new StoreRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('ORDER', 'ORDER_STORE_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $order = $this->orders->add($request->all(), Auth::user()->id);
            print($order);
        }*/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->orders->getById($id, Auth::user()->id);
        if (isset($result->id))
            return $this->sendResponse('ORDER', $result);
        else
            return $this->sendError('ORDER', 'RECORD_NOT_FOUND');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
