<?php

namespace App\Http\Classes;

use App\Models\OrderStatus;

class Statuses
{

    public function get()
    {
        $statuses = OrderStatus::all();
        if (count($statuses) > 0)
            return $statuses;
        else return 0;
    }
}
