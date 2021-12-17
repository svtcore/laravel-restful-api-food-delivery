<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Role;
use Exception;

class UserAuth
{

    /**
     * @param object $request
     * @return json
     * Description: Checking input data if they are correct return user data and access token
     */
    public function login(object $request)
    {
        try {
            $request->request->add([
                "grant_type" => "password",
                "username" => $request->phone_country_code."_".$request->phone_number,
                "password" => $request->password,
                "client_id"     => $request->client_id,
                "client_secret" => $request->client_secret,
            ]);
            $tokenRequest = $request->create(
                env('APP_URL') . '/oauth/token',
                'post'
            );
            $instance = Route::dispatch($tokenRequest);
            $content = json_decode($instance->getContent(), true);
            return $content;
        } catch (Exception $e) {
            return "login error";
        }
    }

    /**
     * @param object $request
     * @return json
     * Description: Create user and give him user role
     */
    public function register(object $request)
    {
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['state'] = "active";
            $user = User::create($input);
            $role = Role::where('slug', 'user')->first();
            $user->roles()->attach($role);
            $success['tokens'] =  $user->createToken('name');
            return $success;
        } catch (Exception $e) {
            return "register error";
        }
    }
}
