<?php

namespace App\Http\Classes;

use App\Models\Product;
use App\Models\ProductCategory;

class Products
{
    public function check_result($result)
    {
        if (json_encode($result) == "null")
            return 0;
        else
            return 1;
    }

    public function get($status = false)
    {
        if ($status)
            $products = Product::with('product_category')->where('available', $status)->get();
        else
            $products = Product::with('product_category')->get();
        if (count($products) > 0)
            return $products;
        else return 0;
    }

    public function getById($id)
    {
        $product = Product::with('product_category')->where('id', $id)->first();
        if ($this->check_result($product))
            return $product;
        else
            return 0;
    }

    public function getByCategoryId($id, $status = false)
    {
        if ($status) {
            $product = Product::with('product_category')
                ->where('category_id', $id)
                ->where('available', 1)->get();
        } else {
            $product = Product::with('product_category')
                ->where('category_id', $id)->get();
        }
        if ($this->check_result($product))
            return $product;
        else
            return 0;
    }

    public function getByRestaurantId($id, $status = false)
    {
        if ($status) {
            $product = Product::with('product_category')
                ->where('restaurant_id', $id)
                ->where('available', 1)->get();
        } else {
            $product = Product::with('product_category')
                ->where('restaurant_id', $id)->get();
        }
        if ($this->check_result($product))
            return $product;
        else
            return 0;
    }

    public function getCategories(){
        $categories = ProductCategory::all();
        if ($this->check_result($categories))
            return $categories;
        else
            return 0;
    }
}
