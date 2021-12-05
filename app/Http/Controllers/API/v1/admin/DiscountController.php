<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\api\v1\BaseController;
use Illuminate\Http\Request;
use App\Http\Classes\Discounts;

class DiscountController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->discounts = new Discounts();
    }

    public function index()
    {
        $result = $this->discounts->getAvailable();
        if (count($result) > 0)
            return $this->sendResponse('DISCOUNT', $result);
        else
            return $this->sendError('DISCOUNT', 'RECORD_NOT_FOUND');
    }
}
