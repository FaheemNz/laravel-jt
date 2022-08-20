<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\ImageServiceInterface;
use App\Utills\Constants\FilePaths;
use App\Utills\Constants\UserRole;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\User;
use DataTables;
use Illuminate\Http\Response;
use Validation;
use Illuminate\Validation\ValidationException;
use Validator;

class UserController extends Controller
{

    protected $ImageService;
    public function __construct(ImageServiceInterface $imageService)
    {
        $this->ImageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $users = User::where("role","<>", UserRole::ADMIN)->latest()->get();
        return view('user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->user_id == '' || $request->user_id == null) {
            return response()->json(null);
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    'first_name'    => 'required|string',
                    'last_name'     => 'required|string',
                    'phone_no'      => 'required|string',
                    'currency_id'   => 'required|exists:currencies,id',
                    'admin_review'  => 'nullable|string',
                ]
            );
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $is_disabled = false;
        if( $request->has('is_disabled') ){
            $is_disabled = true;
        }

        $user = User::updateOrCreate([
            'id' => $request->user_id
        ],[
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'phone_no'      => $request->phone_no,
            'currency_id'   => $request->currency_id,
            'is_disabled'   => $is_disabled,
            'admin_review'  => $request->admin_review,
        ]);


        if($request->new_password) {
            $user->password = bcrypt($request->new_password);
            $user->save();
        }

        return response()->json(['success' => ['User updated successfully']], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin){
            return redirect()->back();
        }
        return view('user.myProfile', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $input_fields = $request->validate([
            'first_name'    =>    'required|string|max:100',
            'last_name'     =>    'required|string|max:100',
            'currency_id'   =>    'required|exists:currencies,id',
            'phone_no'      =>    'required|string|min:6|max:20|unique:users,phone_no,'. $user->id,
            'admin_review'  =>    'nullable|string|max:255',
        ]);

        if($request->new_password) {
            $input_fields['password'] = bcrypt($request->new_password);
        }

        $user->update($input_fields);

        if($request->hasFile("image")){
            $this->ImageService->saveUserAvatar($user, $request->image);
        }


        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function ban($id)
    {
        $user = User::find($id);
        if($user && !$user->isAdmin) {
            $user->is_disabled = !$user->is_disabled;
            $user->save();
        }

        return response()->json(['success'=>'User banned/live successfully.']);
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if($user && !$user->isAdmin) {
            $user->delete();
        }
        return response()->json(['success'=>'User deleted successfully.']);
    }


    public function myProfile()
    {
        $user = auth()->user();
        return view("user.myProfile", compact('user'));
    }
}
