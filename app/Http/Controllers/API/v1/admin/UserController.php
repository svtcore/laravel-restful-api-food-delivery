<?php

namespace App\Http\Controllers\API\v1\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Classes\Users;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->users = new Users();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($this->users->getById($id));
    }
}
