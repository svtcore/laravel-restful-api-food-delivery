<?php

namespace App\Http\Classes;

use App\Models\PaymentMethod;
use Exception;
use App\Http\Traits\ResultDataTrait;

class PaymentMethods
{
    use ResultDataTrait;

    /**
     * @param bool $status
     * @return Collection
     * Description: Getting collection of payment methods with optional param "available"
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
     * @param int $id
     * @return object
     * Description: Getting object of payment method by id
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
