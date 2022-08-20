<?php

namespace App\Http\Controllers\Api\Auth;

use App\Currency;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\ImageServiceInterface;
use App\Utills\Constants\FilePaths;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Validator;
use App\Image;
use App\Lib\Helper;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * @group User Profile
 */
class UserProfileController extends BaseController
{

    protected $ImageService;
    public function __construct(ImageServiceInterface $imageService)
    {
        $this->ImageService = $imageService;
    }

    /**
     * Update user Profile
     *
     * @bodyParam first_name string required First Name of the User
     * @bodyParam last_name string required Last Name of the User
     * @bodyParam currency_id string required ID of the Currency
     * @bodyParam phone_no string required A valid phone number
     * @bodyParam password string A valid password
     * @bodyParam password_confirmation string Same as password if password was provided
     * @bodyParam email string A valid Email
     *
     * @authenticated
     * @response
     * {
     *     "success": true,
     *     "data": {
     *      "user": {
     *          "id": 20,
     *          "role_id": null,
     *          "name": "",
     *          "first_name": "Faheem",
     *          "last_name": "Nawazz",
     *          "email": "mfahimnawaz@gmail.com",
     *          "avatar": "users/default.png",
     *          "phone_no": "1122334455",
     *          "rating": 0,
     *          "currency_id": "1",
     *          "image_id": null,
     *          "settings": null,
     *          "email_verified_at": "2022-03-09T21:06:14.000000Z",
     *          "is_disabled": 0,
     *          "admin_review": null,
     *          "device_token": null,
     *          "created_at": "2022-03-09T21:05:56.000000Z",
     *          "updated_at": "2022-03-15T15:48:50.000000Z",
     *          "deleted_at": null,
     *          "currency": {
     *                  "id": 1,
     *                  "name": "Pakistani Rupee",
     *                  "short_code": "PKR",
     *                  "symbol": "PKR",
     *                  "rate": 178.69999999999999,
     *                  "country_id": 1,
     *                  "created_at": "2022-03-10T15:40:56.000000Z",
     *                  "updated_at": "2022-03-10T15:40:56.000000Z"
     *          },
     *          "image": null
     *   }
     * },
     *    "message": "User Profile updated successfully"
     * }
     */
    public function updateProfile(UpdateProfileRequest $updateProfileRequest)
    {
        $toUpdate = [
            'first_name' => $updateProfileRequest['first_name'],
            'last_name' => $updateProfileRequest['last_name'],
            'currency_id' => $updateProfileRequest['currency_id'],
            'phone_no' => $updateProfileRequest['phone_no'],
        ];
        $shouldSendVerificationEmail = false;

        if($updateProfileRequest->email && $updateProfileRequest->email != auth()->user()->email){
            $duplicateEmailCheck = User::where('email', $updateProfileRequest->email)->first();

            if($duplicateEmailCheck){
                return $this->sendError('Update Profile Error', ['This email already exists! Please provide a different email']);
            }

            $toUpdate['email'] = $updateProfileRequest['email'];
            $toUpdate['email_verified_at'] = null;
            $shouldSendVerificationEmail = true;
        }

        if($updateProfileRequest->password){
            $toUpdate['password'] = bcrypt($updateProfileRequest['password']);
        }

        auth()->user()->update($toUpdate);

        if($shouldSendVerificationEmail){
            auth()->user()->sendEmailVerificationNotification();
        }

        $response = [
            'user' => auth()->user()
        ];
        if ($response['user']['rating']) {
            $response['user']['rating'] = round(auth()->user()->rating, 1);
        }
        $response['user']['currency'] = auth()->user()->currency;
        $response['user']['image'] = auth()->user()->image;
        if ($response['user']['image']) {
            $response['user']['image']['name'] = asset('/avatars') . '/' . auth()->user()->image->name;
        }
        $response['is_email_changed'] = $shouldSendVerificationEmail ? true : false;

        return $this->sendResponse(
            $response,
            'User Profile updated successfully'
        );
    }

    /**
     * Upload Avatar
     *
     * @bodyParam image image required A valid Image (jpg, jpeg, png, bmp, pdf) having max 5120KB size
     *
     * @authenticated
     * @response
     * {
     *     "success": true,
     *     "meessge": "Avatar has been uploaded successfully",
     *     "data"   : []
     * }
     */
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:jpg,jpeg,png,bmp,pdf|max:5120',
        ], [
            'image.mimes' => 'Please provide a valid image',
            'image.max'   => 'The image exceeds maximum size limit (5mb)'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->all(), 'Validation failed', 400);
        }

        if(auth()->user()->image){
            $image = auth()->user()->image;
            $this->ImageService->deleteImage(public_path(FilePaths::ADMIN_PROFILE_IMAGE_DIRECTORY ."/".$image->name));
            $image->delete();
        }

        $file = $request->file('image');

        $file_name        =     $file->getClientOriginalName();
        $file_ext         =     $file->getClientOriginalExtension();
        $file_unique_name =     uniqid() . time() . '_' . $file_name;

        $file->move(public_path(FilePaths::ADMIN_PROFILE_IMAGE_DIRECTORY), $file_unique_name);

        $image = Image::create([
            'original_name' => $file_name,
            'name'          => $file_unique_name,
            'uploaded_by'   => auth()->user()->id
        ]);

        auth()->user()->update([
            'image_id' => $image->id
        ]);

        $image['name'] = asset(FilePaths::ADMIN_PROFILE_IMAGE_DIRECTORY) . '/' . $image['name'];

        return response()->json([
            'success' => true,
            'message' => 'Avatar has been uploaded successfully',
            'data' => $image
        ], 200);
    }

    /**
     * Update Currencies
     *
     * @authenticated
     *
     * @response
     * {
     *     "success": true,
     *     "message": "Currencies have been updated successfully",
     *     "data"   : []
     * }
     */
    public function updateCurrencies()
    {
        $currencies = Currency::get();
        // http://api.exchangeratesapi.io/v1/latest?access_key=615882d18f9992e0de84e5a0d06ad009
        $endpoint = "http://api.exchangeratesapi.io/v1/latest?access_key=615882d18f9992e0de84e5a0d06ad009";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $endpoint);
        $statusCode = $response->getStatusCode();
        $content = json_decode($response->getBody(), true);

        if ($statusCode == 200) {
            $base_euro = $content["rates"];

            $euro_rate = $base_euro["EUR"] / $base_euro["USD"];

            foreach ($currencies as $currency) {
                if (array_key_exists($currency->short_code, $base_euro)) {
                    $currency->rate = $euro_rate * $base_euro[$currency->short_code];
                    $currency->update();
                }
            }

            return $currencies;
        } else {
            Helper::log('### Currency_API Failure ###', []);
        }
    }

    /**
     * Refresh Token
     *
     * @bodyParam token string required The JWT token
     *
     * @authenticated
     * @response
     * {
     *      "success": true,
     *      "message": "Token has been refreshed successfully",
     *      "data"   : []
     * }
     */
    public function refreshToken(Request $request)
    {
        $oClient = DB::table('oauth_clients')->where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', 'http://brrring.polt.pk/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => 'mfahimnawaz@gmail.com',
                'password' => 'password',
                'scope' => '*',
            ]
        ]);

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, 200);
    }

    /**
     * Check email of the logged in user is verified or not
     *
     * @authenticated
     *
     * @response
     * {
     *      "success": true,
     *      "message": "",
     *      "data"   : [{"is_email_verified": true}]
     * }
     */
    public function isEmailVerified(Request $request)
    {
        $is_verified = auth()->user()->isEmailVerified();

        return response()->json([
            'data' => [
                'email_verified_at' => $is_verified? auth()->user()->email_verified_at : null,
            ],
            'success'   => $is_verified,
            'message'   => $is_verified? 'Email is verified' : "Email is not verified yet"
        ]);
    }
}
