<?php

namespace App\Http\Controllers\API\v1\user;

use App\Http\Controllers\api\v1\BaseController;
use Illuminate\Http\Request;
use App\Http\Classes\Orders;
use App\Http\Classes\Products;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\api\v1\user\products\IndexRequest;
use App\Http\Requests\api\v1\user\products\CategoryRequest;
use App\Http\Requests\api\v1\user\products\RestaurantRequest;
use App\Models\ProductCategory;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->products = new Products();
    }

    public function index(Request $request)
    {
        $validation = Validator::make($request->all(), (new IndexRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('PRODUCT', 'PRODUCT_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            if (isset($request->available))
                if ($request->available == 0) $status = false;
                else $status = true;
            else $status = false;
            $products = $this->products->get($status);
            if (count($products) > 0)
                return $this->sendResponse('PRODUCT', $products);
            else
                return $this->sendError('PRODUCT', 'FAILED_GET_PRODUCTS');
        }
    }

    public function show($id)
    {
        $product = $this->products->getById($id);
        if (isset($product->id))
            return $this->sendResponse('PRODUCT', $product);
        else
            return $this->sendError('PRODUCT', 'FAILED_GET_PRODUCT');
    }

    public function showByCategoryId(Request $request, $id)
    {
        $validation = Validator::make($request->all(), (new CategoryRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('PRODUCT', 'PRODUCT_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            if (isset($request->available))
                if ($request->available == 0) $status = false;
                else $status = true;
            else $status = false;
            $products = $this->products->getByCategoryId($id, $status);
            if (count($products) > 0)
                return $this->sendResponse('PRODUCT', $products);
            else
                return $this->sendError('PRODUCT', 'FAILED_GET_PRODUCTS');
        }
    }

    public function showByRestaurantId(Request $request, $id)
    {
        $validation = Validator::make($request->all(), (new RestaurantRequest)->rules());
        if ($validation->fails()) {
            return $this->sendError('PRODUCT', 'PRODUCT_VALIDATION_EXCEPTION', $validation->errors());
        } else {
            if (isset($request->available))
                if ($request->available == 0) $status = false;
                else $status = true;
            else $status = false;
            $products = $this->products->getByRestaurantId($id, $status);
            if (count($products) > 0)
                return $this->sendResponse('PRODUCT', $products);
            else
                return $this->sendError('PRODUCT', 'FAILED_GET_PRODUCTS');
        }
    }
}
