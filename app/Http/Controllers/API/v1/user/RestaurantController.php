<?php

namespace App\Http\Controllers\API\v1\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Classes\Restaurants;
use App\Http\Controllers\API\v1\BaseController;
use App\Http\Requests\api\v1\restaurants\SearchRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $restaurants;

    public function __construct()
    {
        $this->restaurants = new Restaurants();
    }

    public function index()
    {
        $result = $this->restaurants->get();
        if (iterator_count($result) > 0)
            return $this->sendResponse('RESTAURANT', $result);
        else
            return $this->sendError('RESTAURANT', 'RECORDS_NOT_FOUND');
    }

    public function show($id)
    {
        $result = $this->restaurants->getById($id);
        if (isset($result->id))
            return $this->sendResponse('RESTAURANT', $result);
        else
            return $this->sendError('RESTAURANT', 'RECORD_NOT_FOUND');
    }

    public function search(Request $request)
    {
        $validation = Validator::make($request->all(), (new SearchRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('RESTAURANT', 'SEARCH_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            $result = $this->restaurants->search($request->key);
            if (iterator_count($result) > 0)
                return $this->sendResponse('RESTAURANT', $result);
            else
                return $this->sendError('RESTAURANT', 'RECORDS_NOT_FOUND');
        }
    }

    public function showByCategoryId($id)
    {
        $result = $this->restaurants->getByProductCategoryId($id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('RESTAURANT', $result);
        else
            return $this->sendError('RESTAURANT', 'RECORDS_NOT_FOUND');
    }

    public function showByCityId($id)
    {
        $result = $this->restaurants->getByCityId($id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('RESTAURANT', $result);
        else
            return $this->sendError('RESTAURANT', 'RECORDS_NOT_FOUND');
    }

    public function showByProductId($id)
    {
        $result = $this->restaurants->getByProductId($id);
        if (iterator_count($result) > 0)
            return $this->sendResponse('RESTAURANT', $result);
        else
            return $this->sendError('RESTAURANT', 'RECORDS_NOT_FOUND');
    }

    public function showCitiesList()
    {
        $result = $this->restaurants->getCities();
        if (iterator_count($result) > 0)
            return $this->sendResponse('RESTAURANT_CITY', $result);
        else
            return $this->sendError('RESTAURANT_CITY', 'RECORDS_NOT_FOUND');
    }
}
