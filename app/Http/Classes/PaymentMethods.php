<?php

namespace App\Http\Classes;

use App\Models\PaymentMethod;

class PaymentMethods
{
    public function check_result($result)
    {
        if (json_encode($result) == "null")
            return 0;
        else
            return 1;
    }

    public function get($status = false)
    {
        if ($status)
            $payments = PaymentMethod::where('available', $status)->get();
        else
            $payments = PaymentMethod::all();
        if (count($payments) > 0)
            return $payments;
        else return 0;
    }

    public function getById($id)
    {
        $payment = PaymentMethod::where('id', $id)->first();
        if ($this->check_result($payment))
            return $payment;
        else
            return 0;
    }
}
