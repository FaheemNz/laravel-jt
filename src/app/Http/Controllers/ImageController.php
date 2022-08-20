<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use Auth;

class ImageController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images.*' => 'required|mimes:jpg,jpeg,png,bmp,pdf|max:5120',
        ]);

        if ($validator->fails())
        {
            return $this->sendError($validator->errors()->all(),'Validation failed',400);
        }

        $reqImages = $request->file('images');
        $images = [];
        foreach($reqImages as $file)
        {
            $file_name        = $file->getClientOriginalName();
            $file_ext         = $file->getClientOriginalExtension();
            $file_unique_name = uniqid().time().'_'.$file_name;
            $file->move(public_path('images'), $file_unique_name);
            array_push($images,Image::create([
                'original_name' => $file_name,
                'name'          => $file_unique_name,
                'uploaded_by'   => Auth::user()->id
            ]));
        }

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $images
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
    }
}
