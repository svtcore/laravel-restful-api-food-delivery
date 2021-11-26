<?php

namespace App\Http\Controllers\API\v1\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\BaseController as BaseController;
use App\Http\Classes\UserAuth;
use App\Http\Requests\api\v1\auth\LoginRequest;
use Exception;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->user_auth = new UserAuth();
    }

    public function login(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), (new LoginRequest)->rules());
            if ($validation->fails()) {
                return $this->sendError('LOGIN','VALIDATION_EXCEPTION', $validation->errors());
            } else {
                $content = $this->user_auth->login($request);
                if (array_key_exists('access_token', $content))
                    return $this->sendResponse('LOGIN','SUCCESSFUL_AUTHORIZATION', $content);
                else
                    return $this->sendError('LOGIN','WRONG_CREDENTIALS', NULL);
            }
        } catch (Exception $e) {
            return $this->sendError('LOGIN', 'UNKNOWN_ERROR', NULL);
        }
    }
}
