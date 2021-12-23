<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\api\v1\BaseController;
use Illuminate\Http\Request;
use App\Http\Classes\Discounts;

class DiscountController extends BaseController
{
    public function __construct()
    {
        $this->discounts = new Discounts();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->discounts->getAvailable();
        if (iterator_count($result) > 0)
            return $this->sendResponse('DISCOUNT', $result);
        else
            return $this->sendError('DISCOUNT', 'RECORD_NOT_FOUND');
    }
}
