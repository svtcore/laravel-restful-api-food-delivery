<?php

namespace App\Http\Classes;

use App\Http\Traits\ResultDataTrait;
use App\Models\Discount;
use Exception;

class Discounts
{
    use ResultDataTrait;

    /**
     * Getting available not expired discounts collection
     * 
     * @param null
     * @return Collection
     * 
     */
    public function getAvailable(): ?iterable
    {
        try {
            $today = date("Y-m-d H:i:s");
            $discounts = Discount::where('expired', '>=', $today)->get();
            if ($this->check_result($discounts)) return $discounts;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
