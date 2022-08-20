<?php

use App\Http\Controllers\Api\PoliciesController;
use App\Http\Controllers\Api\ReasonController;
use App\Http\Controllers\Api\Auth\UserProfileController;
use App\Http\Controllers\Api\CounterOfferController;
use App\Http\Controllers\Api\OffersController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TimelineController;
use App\Http\Controllers\VerifyEmailController;
use App\Lib\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/*
|--------------------------------------------------------------------------
|                             API Version : 1
|--------------------------------------------------------------------------
|
| Note :
|
*/

### QUERY LOGGING BEGIN ###
DB::listen(function ($query) {
    Helper::log("### SQL QUERY ###", [
        'QUERY'     =>      str_replace('\n', '', $query->sql),
        'BINDINGS'  =>      $query->bindings,
        'TIME'      =>      $query->time . ' ms.'
    ]);
});
### QUERY LOGGING END ###

Route::post('/v1/auth/login', 'Api\Auth\RegisterLoginController@login');
Route::post('/v1/auth/signup', 'Api\Auth\RegisterLoginController@signUp');
Route::resource('/v1/currencies', 'Api\CurrencyController');
Route::resource('/v1/cities', 'Api\CityController');

Route::post('/v1/auth/password/create', 'Api\Auth\PasswordResetController@create');
Route::get('/v1/auth/password/find/{token}', 'Api\Auth\PasswordResetController@find');
Route::post('/v1/auth/password/reset', 'Api\Auth\PasswordResetController@reset');

Route::get('/v1/all-banks', 'Api\BankAccountController@banks');

// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verifyEmail'])
    ->middleware(['throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/v1/email/verify/resend', [VerifyEmailController::class, 'resendVerificationEmail'])
    ->middleware(['auth:api', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/v1/auth/is-email-verified', [UserProfileController::class, 'isEmailVerified'])->middleware(['auth:api']);

Route::post('/v1/convert-amount', [\App\Http\Controllers\Api\CurrencyController::class, 'convertAmount']);

Route::group([
    'prefix' => 'v1',
    'middleware' => ['auth:api', 'verified']
], function () {
    Route::post('/testing', function() {
        return 'Verified response...';
    });

    Route::get("get-policies", [PoliciesController::class,'index']);

    Route::post('/process-payment', [PaymentController::class, 'store']);
    Route::post('/clear-payment/{order}', [PaymentController::class, 'cleared']);
    Route::get('/generate-payment-link/{order}', [PaymentController::class,'generateLink']);


    Route::get('/reason-types', [ReasonController::class, 'reasonTypes']);
    Route::get('/reasons', [ReasonController::class, 'index']);

    Route::post('/auth/refresh-token', [UserProfileController::class, 'refreshToken']);

    Route::group([
        'middleware' => ['cors', 'json.response'],
        'namespace' => 'Api'], function () {
        //?? ======================================= Authentication Routes ==================================
        Route::group([
            'prefix' => 'auth',
            'namespace' => 'Auth'
        ], function () {

            Route::get('update_currencies', 'UserProfileController@updateCurrencies');

            Route::group([
                'middleware' => 'auth:api'
            ], function() {
                Route::post('update-profile', 'UserProfileController@updateProfile');
                Route::post('upload_avatar', 'UserProfileController@uploadAvatar');
                Route::get('logout', 'RegisterLoginController@logout');
            });
        });
        Route::group([
            'middleware' => 'auth:api'
        ], function () {
            //?? ======================================= Order Routes ====================================
            Route::group([
                'prefix' => 'orders',
            ], function () {
                Route::get('{id}/checkTripsForOrder', 'OrderController@checkTripsForOrder');
                Route::post('{id}/disputed', 'OrderController@disputedOrder');
                Route::post('{id}/updateStatus', 'OrderController@updateStatus');
                Route::post('{id}/acceptOffer', 'OrderController@acceptOffer');
                Route::get('{id}/offers', 'OrderController@getOrderOffers');
            });
            Route::resource('orders', 'OrderController');

            //?? ======================================= Reporting Routes =================================
            Route::group([
                'prefix' => 'reports',
            ], function () {
                Route::get('reasons', 'ReportController@getReasons');
            });

            Route::resource('reports', 'ReportController');

            Route::resource('disputes', 'DisputeController');

            //?? ======================================= Advertisement Routes ==============================
            Route::resource('advertisements', 'AdvertisementController');
            //??======================================= Offer Routes =======================================
            Route::resource('offers', 'OffersController')->except(['destroy']);
            Route::post('/offers/{offer}/selected', [OffersController::class, 'selected']);
            Route::post('/offers/{id}/reject', [OffersController::class, 'destroy']);
            //??======================================= Offer Routes =======================================
            Route::post('offers/{id}/counter-offer', [CounterOfferController::class, 'store']);
            Route::post('offers/{id}/counter-offer/reject', [CounterOfferController::class, 'destroy']);
            Route::post('offers/{id}/counter-offer/accept', [CounterOfferController::class, 'accept']);

            //?? ======================================= Trip Routes =======================================
            Route::group([
                'prefix' => 'trips',
            ], function () {
                Route::patch('{id}/changeStatus', 'TripController@changeStatus');
                Route::get('{id}/orders', 'TripController@getTripOrders');
            });
            Route::resource('trips', 'TripController');

            //?? ======================================= Report Routes ======================================
            Route::group([
                'prefix' => 'reports',
            ], function () {
                Route::get('reasons', 'ReportController@getReasons');
            });

            //?? ======================================= Wallet Routes ======================================
            Route::group([
                'prefix' => 'wallet',
            ], function () {
                Route::get('/', 'WalletController@index');
                Route::post('/credit', 'WalletController@credit');
                Route::post('/debit', 'WalletController@debit');
                Route::post('/status', 'WalletController@updateStatus');
            });

            //?? ======================================= Transactions Routes ======================================
            Route::group([
                'prefix' => 'transactions',
            ], function () {
                Route::get('/', 'WalletController@transactions');
            });

            //?? ======================================= Notifications Routes ======================================
            Route::group([
                'prefix' => 'notifications',
            ], function () {
                Route::get('/', 'NotificationController@index');
                Route::post('/mark-as-read', 'NotificationController@store');
            });

            //?? ======================================= Timeline Routes ======================================
            Route::group([
                'prefix' => 'timeline',
            ], function () {
                Route::post('/item-purchased-receipt', [TimelineController::class, 'uploadItemPurchasedReceipt']);
                Route::post('/duty-charges-receipt', [TimelineController::class, 'uploadCustomDutyChargesReceipt']);
                Route::get('/generate-security-code/{id}', [TimelineController::class, 'generateSecurityCode']);
                Route::get('/fetch-security-code/{id}', [TimelineController::class, 'fetchSecurityCode']);
                Route::post('/item-handed-over', [TimelineController::class, 'itemHandedOver']);
                Route::post('/client-review', [TimelineController::class, 'clientReview']);
                Route::post('/traveler-review', [TimelineController::class, 'travelerReview']);
            });

            //?? ======================================= Chat Routes ======================================
            Route::group([
                'prefix' => 'chat-rooms',
            ], function () {
                Route::get('all', 'ChatRoomController@getChatRooms');
                Route::get('{id}/messages', 'ChatRoomController@getChatRoomMessages');
            });
            //Route::resource('chat-rooms', 'ChatRoomController');
            Route::resource('messages', 'MessageController')->except(['destroy']);
            Route::post('/save-token', [App\Http\Controllers\Api\FCMController::class, 'saveToken'])->name('save.token');
            //Route::post('/send-notification', [App\Http\Controllers\Api\FCMController::class, 'sendNotification'])->name('send.notification');
        });
        //?? ======================================= Other Routes ======================================
        // Route::resource('currencies', 'CurrencyController');
        Route::resource('categories', 'CategoryController');
        // Route::resource('cities', 'CityController');
        Route::resource('accounts','BankAccountController');


    });

    //?? ======================================= ImageUpload Routes ======================================
    Route::group([
        'middleware' => ['cors', 'json.response','auth:api']
    ], function () {
        Route::resource('images', 'ImageController');
    });

});

//?? ================================== Fallback Routes (If no route found 404) ========================================
Route::fallback(function () {
    $response = [
        'success' => false,
        'message' => 'No such route exist',
        'data'    => []
    ];
    return response()->json($response, 400);
});

