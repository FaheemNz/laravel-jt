<?php

namespace App\Http\Controllers\ReferenceListsControllers;

use App\Category;
use App\Http\Controllers\Controller;
use App\Lib\Helper;
use App\Services\Interfaces\ImageServiceInterface;
use App\Utills\Constants\FilePaths;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $ImageService;
    public function __construct(ImageServiceInterface $imageService)
    {
        $this->ImageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $categories = Category::all();
        return view('reference_lists.categories', compact("categories"));
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
        $request->validate([
            "name"      => "required|string|unique:categories",
            "tariff"    => "integer",
            "image"     => "image"
        ]);

        $image = asset(FilePaths::BROKEN_IMAGE);
        if($request->hasFile("image")){
            $image_name = Helper::removeSpecialChFromString($request->name);
            $image = $request->image;
            $image = $this->ImageService->saveImage($image, $image_name, FilePaths::CATEGORY_IMAGE_DIRECTORY);
        }

        Category::create([
            "name"      => $request->name,
            "tariff"    => $request->tariff,
            "image_url" => $image,
        ]);

        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return RedirectResponse
     */
    public function destroy(Category $category)
    {
        if($category->image_url){
            $this->ImageService->deleteImage($category->image_url);
        }
        $category->delete();

        return redirect()->route('category.index');
    }
}
