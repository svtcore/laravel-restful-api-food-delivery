<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\Route;
use App\Models\User;

class Users
{
    public function getById($id){
        return json_decode(User::findOrFail($id)->first());
    }
}

?>