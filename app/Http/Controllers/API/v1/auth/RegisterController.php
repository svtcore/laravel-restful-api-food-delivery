<?php

namespace App\Http\Controllers\API\v1\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\BaseController as BaseController;
use App\Http\Requests\api\v1\auth\RegisterRequest;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Http\Classes\UserAuth;
use Exception;

class RegisterController extends BaseController
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

    public function register(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), (new RegisterRequest)->rules());
            if ($validation->fails()) {
                return $this->sendError('REGISTER','VALIDATION_EXCEPTION', $validation->errors());
            } else {
                $result = $this->user_auth->register($request);
                return $this->sendResponse('REGISTER', 'SUCCESSFUL_REGISTRATION', $result);
            }
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062)
                return $this->sendError('REGISTER', 'DUBLICATE_ENTRY_1062', NULL);
            else
                return $this->sendError('REGISTER', 'UNKNOWN_QUERY_ERROR', $e);
        } catch (Exception $e) {
            return $this->sendError('REGISTER', 'UNKNOWN_ERROR', $e);
        }
    }
}
