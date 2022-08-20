<?php

use App\Events\PusherEvent;
use App\Http\Controllers\NotificationController;
use App\Order;
use App\SystemSetting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    "register" => false
]);

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth', 'isAdmin']], function () {

    // Settings Route
    Route::get('/settings', 'SystemSettingsController@index')->name('settings');
    Route::post('/setting/{setting}', 'SystemSettingsController@update')->name('setting.update');

    // Chat Route
    Route::get("/chats", [\App\Http\Controllers\ChatRoomController::class, "index"])->name("chat-room");

    Route::get("/chat/{chatRoom}/messages", [\App\Http\Controllers\ChatRoomController::class, "fetchAllMessagesForChat"]);

    // Dashboard Route
    Route::get('/home', 'HomeController@index')->name('home');

    // Notification Route
    Route::view("/notifications", "notifications.index")->name("notifications");
    Route::get("/notifications/markRead/{notification}", [NotificationController::class, "markRead"])->name("notification.markRead");
    Route::get("/notifications/delete/{notification}", [NotificationController::class, "delete"])->name("notification.delete");
    Route::get("/notifications/markAllRead", [NotificationController::class, "markAllRead"])->name("notifications.markAllRead");
    Route::get("/notifications/deleteAll", [NotificationController::class, "deleteAll"])->name("notifications.deleteAll");

    // User Routes
    Route::get('/users/{id}/ban','UserController@ban');
    Route::get('/my-profile','UserController@myProfile')->name("profile");
    Route::resource('users', 'UserController');

    // Order Routes
    Route::get('/orders/{id}/disable','OrderController@ban');
    Route::resource('orders', 'OrderController');

    // Offer Routes
    Route::get('/offers/{id}/disable','OfferController@ban');
    Route::resource('offers', 'OfferController');

    // Counter Offer Routes
    Route::resource('counter-offers', "CounterOfferController");

    // Trip Routes
    Route::resource('trips', 'TripController');

    // Payment Routes
    Route::get("payments", 'PaymentController@index')->name('payments.index');
    Route::get("payments/clear/{order}", 'PaymentController@clearPayment')->name('payment.clear');

    Route::get("clear-customer-payment/{order}", 'PaymentController@clearCustomerPayment')->name("clear-customer-payment");
    Route::get("clear-traveller-payment/{order}", 'PaymentController@clearTravellerPayment')->name("clear-traveller-payment");

    // Transaction Routes
    Route::get("transactions", 'TransactionController@index')->name("transactions.index");

    // Report Routes
    Route::resource('reports', 'ReportController');

    // Disputes Routes
    Route::resource('disputes', "DisputeController");

    // Announcement Routes
    Route::get("announcements", "AnnouncementController@index")->name('announcements');
    Route::post("announcements", "AnnouncementController@store")->name('announcement.store');

    // Reference List Routes
    Route::view("reference-list", "reference_lists.index")->name("referenceList");

    Route::resource('banks', "ReferenceListsControllers\BankController");

    Route::resource('category', "ReferenceListsControllers\CategoryController");
    Route::resource('currency', "ReferenceListsControllers\CurrencyController");
    Route::resource('reason', "ReferenceListsControllers\ReasonController");

    Route::resource('country', "ReferenceListsControllers\CountryController");
    Route::resource('state', "ReferenceListsControllers\StateController");
    Route::resource('city', "ReferenceListsControllers\CityController");
});

Route::get("currency-cron", function (){
    Artisan::call("currency_rate:cron");
    return redirect()->back();
})->name("UpdateCurrency");





Route::get('/offers/{offer_id}/process_payment', 'OrderController@acceptOfferPayment');
// Route::post('/orders/{$id}/accepted', 'OrderController@acceptedOffer');

// Route::resource('offers', 'OfferController');

//! TRIPs ROUTES
// Route::resource('trips', 'TripsController');
// Route::post('schedule_trip','TripsController@store');
// Route::get('/my_trips','TripsController@myTrips');
// Route::get('/my_current_trip','TripsController@currentTrip');
// Route::post('/update_trip','TripsController@update');

// //! ORDERs ROUTES
// Route::resource('orders', 'OrdersController');
// Route::get('/my_orders','OrdersController@myOrders');

//! OFFERs ROUTES
// Route::resource('offers', 'OffersController');
// Route::post('post_offer','OffersController@store');


//! FIND CITY ROUTE
// Route::get('/cities','LocationController@findCity');
// Route::post('/orders/{order_id}/{status}','OrdersController@paymentCompleted');

Route::post('/paymentCallback', function () {
    $response = [
        'success' => true,
        'data'    => [],
        'message' => '',
    ];
    return response()->json($response, 200);
});

// Route::get('/updateOrder', function () {
//     $order = Order::where('id',1)->where('is_disabled',false)->first();
//     $order->status = 'tip';
//     $order->save();
//     $order->status = 'paid';
//     $order->save();
//     return 'Successfully Updated';
// });


// Route::get('/updateCategories', function () {
//     $categories = Category::all();
//     foreach($categories as $category) {
//         $category->image_url = '/categories/category_1.png';
//         $category->save();
//     }
//     return 'Successfully Updated';
// });

//Route::get('/fire', function () {
//    // $result = [
//    //     'order_id' => 11,
//    //     'status'   => 'handover'
//    // ];
//    // event(new \App\Events\OrderStatusToUser($result));
//    // dd('Event Run Successfully.');
//    // Order::find('15')->update([
//    //     'status' => 'new'
//    // ]);
//    $response = Http::post('http://35.222.29.47:3100/updateOrder', []);
//    dd($response);
//    //   \Artisan::call('cache:clear');
//    //     \Artisan::call('config:clear');
//    //       \Artisan::call('route:clear');
//});


// Route::get('socket',function() {
//     return view('socket');
// });
Route::get('/socket', 'HomeController@socket')->name('socket');
Route::get('getAllCategories','SharedController@getAllCategories');
Route::get('getOrderAllStatuses','SharedController@getOrderAllStatuses');
Route::get('getOfferAllStatuses','SharedController@getOfferAllStatuses');
Route::get('getAllCurrencies','SharedController@getAllCurrencies');
Route::get('getAllCities','SharedController@getAllCities');
Route::get('getAllStates','SharedController@getAllStates');
Route::get('getAllCountries','SharedController@getAllCountries');
Route::get('getAllUsers','SharedController@getAllUsers');


Route::get('/check', function() {
    return view('email-verified');
});

Route::get('/privacy', function() {
    return view('privacy');
});
Route::get('/terms', function() {
    return view('terms_and_conditions');
});

Route::get('/forgot-password', function () {
    return view('auth.passwords.forgot');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $validator = Validator::make($request->all(),
        [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|max:100|confirmed'
        ],
        [
            'email.email'           =>      'Please provide a valid Email',
            'email.exists'          =>      'The email you provided does not exist in our system',
            'password.min'          =>      'Password must be atleast 8 characters long',
            'password.max'          =>      'Password must be less than 100 characters',
            'password.confirmed'    =>      'Passwords does not match'
        ]
    );

    if($validator->fails()){
        return back()->withErrors($validator);
    }

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
                ? view('auth.passwords.success')
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

Route::fallback(function() {
    return view('404');
});

//Route::get('pam:fs', function () {
//    Artisan::call('migrate:fresh --seed');
//    Artisan::call('passport:install');
//    dd("Migrations Run Successfully"); EDMAIL WALA KAM ? exception ko JSON ki bjay HTML ?
//});

Route::get('clear', function(){
    Artisan::call('cache:clear');   // Run the following command to clear the application cache of the Laravel application.
    Artisan::call('route:clear');   // To clear route cache of your Laravel application execute the following command from the shell.
    Artisan::call('config:clear');  // You can use config:clear to clear the config cache of the Laravel application.
    Artisan::call('view:clear');    // Also, you may need to clear compiled view files of your Laravel application. To clear compiled view files run the following command from the terminal.
    Artisan::call('optimize:clear');
    Artisan::call('clear-compiled');
//    Artisan::call('optimize');
    dd("All type of cache clear (cache, route, config, view, clear-compiled)");
});

Route::get("pusher", function (){
    dd("Not Here");
});

Route::get('nift/{token}', function($token){
    $token = \App\Lib\Helper::deStructureNiftToken($token);
    $nift_link_refresh_time = SystemSetting::where("key", "nift_link_refresh_time")->first()->value;
    if($token->time > $nift_link_refresh_time){
        $message = "Link Has Expired, Please Visit Link Again From APP.";
        return view('custom-error', compact('message'));
    }

    $order   = Order::find($token->order);
    if(!$order){
        $message = "Order Does Not Exists.";
        return view('custom-error', compact('message'));
    }

    $payment = \App\Payment::where([
        'order_id'=> $order->id,
        'user_id'=> $token->user
    ])->first();

    if(!$payment){
        $message = "No Offer or Counter Offer Accepted.";
        return view('custom-error', compact('message'));
    }

    $settings = (object)[
        'salt'          => "XFgJzjdk0jY=",
        'password'      => "ydQKxki2k9A=",
        'merchant_id'   => "MR5016",
        'redirect_url'  => "https://brrring.polt.pk/test-nift-v2",
        'order_id'      => $order->id,
        'amount'        => $payment->pkr_amount? $payment->pkr_amount * 100 : 100,
        'description'   => $order->description,
        'bill_id'       => bin2hex($order->user_id),
        'ref_id'        => bin2hex($order->id . "jojo" . $payment->id)
    ];
    return view("nift.index", compact('settings'));

})->name("nift-pay");

Route::any('test-nift-v2', function(Request $request){
    if($request->has('pp_ResponseCode')){
        $status  = $request->pp_ResponseCode;
        $orderId = $request->pp_BillReference;
        $refNo   = $request->pp_TxnRefNo;
        $amount  = $request->pp_Amount / 100;
        $message = $request->pp_ResponseMessage;

        $order   = Order::find($orderId);
        if($status == '000' && $order && !\App\Transaction::where('ref_no', $refNo)->exists()){
            $order = Order::find($orderId);
            $paymentService = new \App\Services\PaymentService();
            $isCleared = $paymentService->clearPayment($order,$refNo);
        }

         $data = (object)[
             'status'    => $status,
             'order_id'  => $orderId,
             'refNo'     => $refNo,
             'amount'    => $amount,
             'message'   => $message
         ];
        return view('nift.callback', compact('data'));
    }else{
        return "Not Authorized";
    }
});

