<?php
namespace App\Http\Controllers\Api\Auth;

use App\Currency;
use App\Http\Controllers\BaseController;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\User;
use App\Image;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

/**
 * @group Authentication
 *
 * Registeration and Login
 *
 */
class RegisterLoginController extends BaseController
{
    /**
     *
     * Registeration
     *
     * @bodyParam first_name string required First Name
     * @bodyParam last_name string required Last Name
     * @bodyParam email string required A valid and Unique Email Address
     * @bodyParam password string required Password (Minimum 8 Characters)
     * @bodyParam currency_id integer Currency ID
     * @bodyParam image_id integer Image ID
     * @bodyParam phone_no integer required A valid Phone Number
     *
     * @response
     * {
     * "success": true,
     * "data": {
     *    "user": {
     *           "first_name": "John",
     *           "last_name": "Doe",
     *           "currency_id": "1",
     *           "email": "JohnDoe@gmail.com",
     *           "phone_no": "1122334455",
     *           "image_id": null,
     *           "rating": 0.0,
     *           "updated_at": "2022-03-05T14:05:39.000000Z",
     *           "created_at": "2022-03-05T14:05:39.000000Z",
     *           "id": 13
     *       },
     *       "access_token": "eyeasd.."
     *  },
     *  "message": "You are registered successfully"
     * }
     *
     * @response 422
     * {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *       "email": [
     *           "The email has already been taken."
     *       ]
     *   }
     * }
     */
    public function signUp(RegisterRequest $registerRequest)
    {
        $user = User::create([
            'first_name'        =>    $registerRequest->first_name,
            'last_name'         =>    $registerRequest->last_name,
            'currency_id'       =>    $registerRequest->currency_id,
            'email'             =>    $registerRequest->email,
            'password'          =>    bcrypt($registerRequest->password),
            'phone_no'          =>    $registerRequest->phone_no,
            'image_id'          =>    $registerRequest->image_id ?? null,
            'rating'            =>    0.0,
            'country'           =>    $registerRequest->country ?? null,
            'email_verified_at' =>    NULL
        ]);

        event(new Registered($user));

        $tokenResult = $user->createToken('authToken');
        $accessToken = $tokenResult->accessToken;
//        $accessToken = $user->createToken('authToken')->accessToken;
        $user->token = "Bearer ".$accessToken;

        return $this->sendResponse(
            [
                'user' => $user,
                'token' => "Bearer ".$accessToken,
                'email_verified' => $user->isEmailVerified(),
                'currency' => ['symbol' => $user->currency],
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                'customer_rating' => 0,
                'traveler_rating' => 0
            ],
            'You are registered successfully',
            201
        );
    }

    /**
     *
     * Login
     *
     * @bodyParam email string required Verified Email of the User
     * @bodyParam password string required Password
     *
     * @response
     * {
     * "success": true,
     * "data": {
     *   "user": {
     *       "id": 13,
     *       "role_id": null,
     *       "name": "",
     *       "first_name": "John",
     *       "last_name": "Doe",
     *       "email": "JohnDoe@gmail.com",
     *       "avatar": "users/default.png",
     *       "phone_no": "1122334455",
     *       "rating": 0,
     *       "currency_id": 1,
     *       "image_id": null,
     *       "settings": null,
     *       "email_verified_at": null,
     *       "is_disabled": 0,
     *       "admin_review": null,
     *       "device_token": null,
     *       "email_verified_at": null,
     *       "created_at": "2022-03-05T14:05:39.000000Z",
     *       "updated_at": "2022-03-05T14:05:39.000000Z",
     *       "deleted_at": null,
     *       "currency": null,
     *       "image": []
     *   },
     *   "token": "Bearer eyJ0eXAi...",
     *   "expires_at": "2023-03-05 14:08:05",
     *   "customer_rating": 0,
     *   "traveler_rating": 0
     * },
     * "message": "You are Logged in successfully"
     * }
     *
     * @response 422
     * {
     *   "success": false,
     *   "message": [
     *       "The email field is required.",
     *       "The password field is required."
     *   ],
     *   "data": "Login validation failed"
     * }
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'             =>      'required|string|email:filter|exists:users,email|max:50',
            'password'          =>      'required|string|max:100',
        ], [
            'email.required'    =>      'Email is required',
            'email.exists'      =>      'The email you provided does not exist in our system. Please signup first',
            'email.email'       =>      'Email is not in valid format',
            'email.max'         =>      'Email is too long',
            'password.required' =>      'Password is required',
            'password.max'      =>      'Password value is too long'
        ]);

        if ($validator->fails()){
            return $this->sendError('Login validation failed', $validator->errors()->all(), 422);
        }

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)){
            return $this->sendError('Invalid email/password', ['Invalid email/password'], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('brring');
        $user->currency = Currency::find($user->currency_id);

        if($user->rating) {
            $user->rating = round($user->rating, 1);
        }

        if($user->image_id) {
            $user->image = Image::find($user->image_id);

            if($user->image) {
                $user->image->name = asset('/avatars').'/'.$user->image->name;
            }
        } else {
            $user->image = [];
        }
        $user->token = 'Bearer ' . $tokenResult->accessToken;

        $response = [
            'user' => $user,
            'email_verified' => $user->isEmailVerified(),
            'token' => 'Bearer ' . $tokenResult->accessToken,
            'currency' => ['symbol' => $user->currency],
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'customer_rating' => 0,
            'traveler_rating' => 0
        ];

        return $this->sendResponse($response, 'You are Logged in successfully..');
    }

    /**
     * Logout user (Revoke the token)
     *
     * @authenticated
     * @response
     * {
     *    "success": true,
     *    "data": "",
     *    "message": "Successfully logged out"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->sendResponse('', 'Successfully logged out');
    }

    /**
     * Get the authenticated User
     *
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Email Verification
     *
     * @bodyParam id integer ID of the User
     *
     * @response
     * {
     *  'success': true,
     *  'message': 'Email has been verified',
     *  'data': []
     * }
     *
     * @response 400
     * {
     *  'success': true,
     *  'message': 'Email has already been verified',
     *  'data': []
     * }
     *
     */
    public function verifyEmail(Request $request)
    {
        $id = $request->id;
        if(!is_int($id)){
            return $this->sendError('Email Error', ['Please provide a valid user id'], 422);
        }

        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'Email has already been verified',
                'data'    => []
            ], 400);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'success' => true,
            'message' => 'Email has been verified',
            'data'    => []
        ], 200);
    }

    /**
     * Update the User
     *
     * @authenticated
     */
    public function update(Request $request, RegisterRequest $registerRequest)
    {
        $user = User::findOrFail($request->id)->update(
            [
                'id' => $request->id
            ],
            [
                'first_name'  => $registerRequest->first_name,
                'last_name'   => $registerRequest->last_name,
                'currency_id' => $registerRequest->currency_id,
                'email'       => $registerRequest->email,
                'phone_no'    => $registerRequest->phone_no,
                'image_id'    => $registerRequest->image_id
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Your profile has been updated',
            'data'    => [$user]
        ], 200);
    }
}
