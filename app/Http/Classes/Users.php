<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Exception;

class Users
{
    /**
     * Getting user data by id
     * 
     * @param int $id
     * @return string
     * 
     */
    public function getById(int $id): string
    {
        return json_decode(User::findOrFail($id)->first());
    }

    /**
     * Getting user data by phone number
     * 
     * @param int $id
     * @return object
     * 
     */
    public function getByPhoneNumber(int $phone): ?object
    {
        try {
            $user = User::where('phone_number', $phone)->first();
            if (isset($user->id))
                return $user;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Getting user data by id
     * 
     * @param string $first_name, int $country_code, int $phone_number
     * @return object
     * 
     */
    public function addByAdmin(string $first_name, int $country_code, int $phone_number): ?object
    {
        try {
            $user = User::create([
                'first_name' => $first_name,
                'phone_country_code' => $country_code,
                'phone_number' => $phone_number,
                'password' => bcrypt('password'),
                'state' => 'active',
            ]);
            if (isset($user->id)) return $user;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
