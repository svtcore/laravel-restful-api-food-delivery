<?php

namespace App\Http\Classes;

use App\Models\Discount;
use Exception;

class Discounts
{
    public function check_result($result)
    {
        if (json_encode($result) == "null")
            return 0;
        else
            return 1;
    }

    public function getAvailable()
    {
        $today = date("Y-m-d H:i:s");
        $discounts = Discount::where('expired', '>=', $today)->get();
        if ($this->check_result($discounts))
            return $discounts;
        else
            return 0;
    }
}
