<?php

namespace App\Http\Traits;
use App\Models\User;

trait ResultDataTrait {

    public function check_result($result): bool
    {
        if (json_encode($result) == "null")
            return 0;
        else
            return 1;
    }

    public function check_restaurant_access($user_id, $restaurant_id, $address_id): bool
    {
        $restaurants = User::with(
            'restaurants',
            'restaurants.restaurant_addresses',
            'restaurants.restaurant_addresses.restaurant_city',
            'restaurants.restaurant_addresses.restaurant_street_type',
            'restaurants.delivery_types'
        )
            ->where('id', $user_id)->get();
        if ($this->check_result($restaurants)) {
            if (isset($restaurants[0]['restaurants'])) {
                if (count($restaurants[0]['restaurants']) > 0) {
                    for ($i = 0; $i < count($restaurants[0]['restaurants']); $i++) {
                        $rest_id = intval($restaurants[0]['restaurants'][$i]['id']);
                        if ($rest_id == $restaurant_id) {
                            if ($address_id != NULL) {
                                for ($j = 0; $j < count($restaurants[0]['restaurants'][$i]['restaurant_addresses']); $j++) {
                                    $addr_id = intval($restaurants[0]['restaurants'][$i]['restaurant_addresses'][$j]['id']);
                                    if ($addr_id == $address_id) return 1;
                                }
                            } else return 1;
                        }
                    }
                    return 0;
                }
            } else return 0;
        } else return 0;
    }


}
