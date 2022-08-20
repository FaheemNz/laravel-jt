<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Resources\UserCategoryResource;
use App\Http\Controllers\BaseController;

/**
 * @group Category
 * 
 */
class CategoryController extends BaseController
{
    /**
     * 
     * Get a List of categories
     * 
     * @bodyParam name string required Category Name
     * 
     * @response
     * {
     * "success": true,
     * "data": {
     *   "data": [
     *       {
     *           "id": 1,
     *           "name": "Home & Garden",
     *           "tariff": null,
     *           "image_url": "http://localhost/categories/Home&Garden.png"
     *       },
     *       {
     *           "id": 2,
     *           "name": "Luggage & Bags",
     *           "tariff": null,
     *           "image_url": "http://localhost/categories/Luggage&Bags.png"
     *       }
     *   ]
     * },
     * "message": "Get categories successfully"
     * }
     */
    public function index()
    {
        $category_name = request()->input('name');
        
        if( !is_null($category_name) ) {
            return $this->sendResponse(
                UserCategoryResource::collection(
                    Category::where('name', 'LIKE', '%' . strtolower($category_name) . '%')
                        ->get()
                )->response()->getData(true),
                'Get categories successfully'
            );
        }
        
        return $this->sendResponse(
            UserCategoryResource::collection(
                Category::latest()->get()
            )->response()->getData(true),
            'Get categories successfully'
        );
    }
}
