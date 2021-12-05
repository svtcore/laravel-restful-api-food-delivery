<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\api\v1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Classes\PaymentMethods;
use App\Http\Requests\api\v1\user\payment_methods\IndexRequest;

class PaymentMethodController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->payments = new PaymentMethods();
    }

    public function index(Request $request)
    {
        $validation = Validator::make($request->all(), (new IndexRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('PAYMENT_METHOD', 'PAYMENT_METHOD_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            if (isset($request->available))
                if ($request->available == 0) $status = false;
                else $status = true;
            else $status = false;
            $payments = $this->payments->get($status);
            if (iterator_count($payments) > 0)
                return $this->sendResponse('PAYMENT_METHOD', $payments);
            else
                return $this->sendError('PAYMENT_METHOD', 'FAILED_GET_PAYMENT_METHODS');
        }
    }

    public function show($id)
    {
        $payment = $this->payments->getById($id);
        if (isset($payment->id))
            return $this->sendResponse('PAYMENT_METHOD', $payment);
        else
            return $this->sendError('PAYMENT_METHOD', 'FAILED_GET_PAYMENT_METHOD');
    }

}
