<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\Route;
use App\Models\User;

class Users
{
    public function getById($id){
        return json_decode(User::findOrFail($id)->first());
    }

    public function getByPhoneNumber($phone){
        $user = User::where('phone_number', $phone)->first();
        if (isset($user->id))
            return $user;
        else return NULL;
    }

    public function addByAdmin($first_name, $country_code, $phone_number){
        $user = User::create([
            'first_name' => $first_name,
            'phone_country_code' => $country_code,
            'phone_number' => $phone_number,
            'password' => bcrypt('password'),
            'state' => 'active',
        ]);
        return $user;
    }
}

?>