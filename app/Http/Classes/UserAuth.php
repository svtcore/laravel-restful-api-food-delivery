<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Role;

class UserAuth
{

    public function login($request)
    {
        $request->request->add([
            "grant_type" => "password",
            "username" => $request->email,
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
    }

    public function register($request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['state'] = "active";
        $user = User::create($input);
        $role = Role::where('slug', 'user')->first();
        $user->roles()->attach($role);
        $success['tokens'] =  $user->createToken('name');
        return $success;
    }
}
