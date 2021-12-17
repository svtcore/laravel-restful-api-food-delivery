<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Exception;

class Users
{
    /**
     * @param int $id
     * @return json
     * Description: Getting user data by id
     */
    public function getById(int $id): string
    {
        return json_decode(User::findOrFail($id)->first());
    }

    /**
     * @param int $id
     * @return object
     * Description: Getting user data by phone number
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
     * @param string $first_name, int $country_code, int $phone_number
     * @return json
     * Description: Getting user data by id
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
