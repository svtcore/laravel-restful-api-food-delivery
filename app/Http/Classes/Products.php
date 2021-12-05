<?php

namespace App\Http\Classes;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Traits\ResultDataTrait;
use Exception;

class Products
{
    use ResultDataTrait;

    /**
     * Input: boolean or null
     * Output: collection or null
     * Description: Getting collection of products depends from optional param
     */
    public function get(?bool $status = false): ?iterable
    {
        try {
            if ($status)
                $products = Product::with('product_category')->where('available', $status)->get();
            else
                $products = Product::with('product_category')->get();
            if (iterator_count($products) > 0)
                return $products;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: product id
     * Output: object or null
     * Description: Getting collection of products depends from optional param
     */
    public function getById(int $id): ?object
    {
        try {
            $product = Product::with('product_category')->where('id', $id)->first();
            if ($this->check_result($product)) return $product;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: product id, boolean or null
     * Output: collection or null
     * Description: Getting collection of products by category id
     */
    public function getByCategoryId(int $id, ?bool $status = false): ?iterable
    {
        try {
            if ($status) {
                $products = Product::with('product_category')
                    ->where('category_id', $id)
                    ->where('available', 1)->get();
            } else {
                $products = Product::with('product_category')
                    ->where('category_id', $id)->get();
            }
            if ($this->check_result($products)) return $products;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: product id, boolean or null
     * Output: collection or null
     * Description: Getting collection of products by restaurant id
     */
    public function getByRestaurantId(int $id, ?bool $status = false): ?iterable
    {
        try {
            if ($status) {
                $products = Product::with('product_category')
                    ->where('restaurant_id', $id)
                    ->where('available', 1)->get();
            } else {
                $products = Product::with('product_category')
                    ->where('restaurant_id', $id)->get();
            }
            if ($this->check_result($products)) return $products;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Input: None
     * Output: collection or null
     * Description: Getting collection of product categories
     */
    public function getCategories(): ?iterable
    {
        try {
            $categories = ProductCategory::all();
            if ($this->check_result($categories)) return $categories;
            else return NULL;
        } catch (Exception $e) {
            return NULL;
        }
    }
}
