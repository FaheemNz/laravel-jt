<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Advertisement;
use App\Http\Resources\HomeAdvertisementResource;
use Validation;
use Illuminate\Validation\ValidationException;
use Auth;
class AdvertisementController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources
     */
    public function index()
    {
        $query = Advertisement::with('category','currency','images');
        
        //?? =============== Get Predefined Query Values ====================
        $category = request()->input('category');
        
        //?? ================ Add Category Filter If Exist ==================
        if($category) {
            if(is_array($category)) {
                if(count($category) > 0) {
                    $query->whereIn('category_id',$category);
                }
            } else {
                $query->where('category_id',$category);
            }
        }
        return $this->sendResponse(
            HomeAdvertisementResource::collection($query->latest()->paginate(request()->get('perPage', 10)))
            ->response()->getData(true),
             'Get advertisements successfully');
    }

  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Resources
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources
     */
    public function store(Request $request)
    {
   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources
     */
    public function show($id)
    {
       
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources
     */
    public function update(Request $request,$id)
    {   

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources
     */
    public function destroy($id)
    {

    }

}
