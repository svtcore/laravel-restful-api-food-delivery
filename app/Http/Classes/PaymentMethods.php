<?php

namespace App\Http\Classes;

use App\Models\PaymentMethod;
use Exception;
use App\Http\Traits\ResultDataTrait;

class PaymentMethods
{
    use ResultDataTrait;

    /**
     * Getting collection of payment methods with optional param "available"
     * 
     * @param bool|null $status
     * @return Collection|null
     * 
     */
    public function get(?bool $status = false): ?iterable
    {
        try {
            if ($status)
                $payments = PaymentMethod::where('available', $status)->get();
            else
                $payments = PaymentMethod::all();
            if (iterator_count($payments) > 0) return $payments;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Getting object of payment method by id
     * 
     * @param int $id
     * @return object|null
     * 
     */
    public function getById(int $id): ?object
    {
        try {
            $payment = PaymentMethod::where('id', $id)->first();
            if ($this->check_result($payment)) return $payment;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
