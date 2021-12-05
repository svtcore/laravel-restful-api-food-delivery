<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\api\v1\BaseController;
use Illuminate\Http\Request;
use App\Http\Classes\Statuses;
class StatusController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->statuses = new Statuses();
    }

    public function index()
    {
        $result = $this->statuses->get();
        if (iterator_count($result) > 0)
            return $this->sendResponse('ORDER_STATUS', $result);
        else
            return $this->sendError('ORDER_STATUS', 'RECORDS_NOT_FOUND');
    }
}
