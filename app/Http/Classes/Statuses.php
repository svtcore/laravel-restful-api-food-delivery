<?php

namespace App\Http\Classes;

use App\Models\OrderStatus;
use Exception;

class Statuses
{

    /**
     * Getting collection of order statuses
     * 
     * @param null
     * @return Collection|null
     * 
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
