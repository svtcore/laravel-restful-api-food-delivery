<?php
namespace App\Http\Controllers\API\v1;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($module_name, $message, $result)
    {
      $response = [
            'success' => true,
            'module' => $module_name,
            'message' => $message,
            'data'    => $result,
        ];
        return response()->json($response, 200);
    }
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($module_name, $message, $errorMessages = [], $code = 404)
    {
      $response = [
            'success' => false,
            'module' => $module_name,
            'message' => $message,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}