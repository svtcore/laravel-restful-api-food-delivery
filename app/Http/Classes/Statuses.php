<?php

namespace App\Http\Classes;

use App\Models\OrderStatus;
use Exception;

class Statuses
{

    /**
     * Input: None
     * Output: collection or null
     * Description: Getting collection of order statuses
     */
    public function get(): ?iterable
    {
        try {
            $statuses = OrderStatus::all();
            if (iterator_count($statuses) > 0)
                return $statuses;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
