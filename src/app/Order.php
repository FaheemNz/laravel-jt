<?php

namespace App;

use App\Lib\Helper;
use App\Notifications\OrderNotification;
use App\Services\ImageService;
use App\Utills\Constants\FilePaths;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use SoftDeletes;
    const PAGINATION_ITEMS = 10;

    protected $guarded = [];

    public static $statusArray = [
        'new' => 1,
        'tip' => 2,
        'paid' => 3,
        'purchased' => 4,
        'tracking' => 5,
        'handed' => 6,
        'received' => 7,
        'scanned' => 8,
        'traveler_rated' => 9,
        'customer_rated' => 10,
        'rated' => 11,
        'traveler_paid' => 12
    ];

    public static $weightArray = [
        1   => 'light',
        2   => 'medium',
        3   => 'heavy'
    ];

    public static function saveOrderImages(array $images, int $order_id, $type="customer")
    {
        $userId = auth()->user()->id;
        $locations = [];

        $ImageService = new ImageService();

        foreach ($images as $key => $image) {
//          New Save Image Code
            $uniqueName = $order_id."_" .$type .'.' . $image->getClientOriginalExtension();
            $fileName   = $image->getClientOriginalName();
            $uniqueName = Helper::getUniqueImageName($uniqueName);

            $loc  = $ImageService->saveImage($image, $uniqueName, FilePaths::BASE_IMAGES_PATH);
            $locations[] = $uniqueName;

            $img_obj = Image::create([
                'original_name' => $fileName,
                'uploaded_by'   => $userId,
                'name'          => $uniqueName
            ]);

            ImageOrder::create([
                'image_id' => $img_obj->id,
                'order_id' => $order_id,
                'type'     => $type
            ]);

//            OLD Save Image Code

//            $uniqueName = $order_id."_" .$type .'.' . $image->getClientOriginalExtension();
//            $fileName   = $image->getClientOriginalName();
//            $uniqueName = Helper::getUniqueImageName($uniqueName);
//
//            $img_obj = Image::create([
//                'original_name' => $fileName,
//                'uploaded_by'   => $userId,
//                'name'          => $uniqueName
//            ]);
//
//            ImageOrder::create([
//                'image_id' => $img_obj->id,
//                'order_id' => $order_id,
//                'type'     => $type
//            ]);
//
//            $locations[] = public_path(FilePaths::BASE_IMAGES_PATH)."/".$uniqueName;
//
//            $image->move(public_path(FilePaths::BASE_IMAGES_PATH), $uniqueName);
        }

        return $locations;
    }

    public function getWeightString()
    {
        return $this::$weightArray[$this->weight];
    }

    public function getStatusValue()
    {
        return $this::$statusArray[$this->status];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function traveller()
    {
        return $this->belongsTo(User::class, "traveler_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function sourceCity()
    {
        return $this->belongsTo('App\City', 'from_city_id', 'id');
    }
    public function destinationCity()
    {
        return $this->belongsTo('App\City', 'destination_city_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class);
    }

    public function imageOrder()
    {
        return $this->hasMany(ImageOrder::class, 'order_id');
    }

    public function getCompleteSourceAddressAttribute()
    {
        if (!$this->sourceCity) {
            return '';
        }

        return "{$this->sourceCity->name} , {$this->sourceCity->state->country->name}";
    }

    public function getCompleteDestinationAddressAttribute()
    {
        if (!$this->destinationCity) {
            return '';
        }

        return "{$this->destinationCity->name} ,  {$this->destinationCity->state->country->name}";
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function counterOffers()
    {
        return $this->hasMany(CounterOffer::class);
    }

    // public function reports()
    // {
    //     return $this->hasMany(Report::class,'entity_id','id');
    // }

    public function validatePinCode($value)
    {

        if (!$this->pin_time_to_live || !$this->pin_code) {
            return false;
        }
        // if(!Carbon::parse($this->pin_time_to_live)->gte(Carbon::now())) {
        //     return false;
        // }
        return $this->pin_code === $value;
    }

    public static function getCalculatedOrderPrice($price, $amount)
    {
        return $price * $amount;
    }

    public static function sendNotification(Order $order, $user_id, string $type)
    {
        if (is_array($user_id)) {
            $users = User::find($user_id);
            foreach ($users as $user) {
                $user->notify(new OrderNotification($order, $type));
            }
        } else {
            $user = User::findOrFail($user_id);
            $user->notify(new OrderNotification($order, $type));
        }
    }

    public static function getMessages(string $type)
    {
        return [
            'create'        =>  'A New order has been created',
            'accept'        =>  'Your offer have been accepted',
            'reject'        =>  'Your offer have been rejected',
            'purchase'      =>  'Your item has been purchased',
            'code_generate' =>  'OTP generated against your order, kindly confirm',
            'review'        =>  'Your service has been reviewed and rated',
        ][$type];
    }

    public static function getOrderImageUrl(string $orderName)
    {
        return asset('public/images/' . $orderName);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
