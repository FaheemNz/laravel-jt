<?php

namespace App;

use App\Utills\Constants\UserRole;
use Illuminate\Auth\MustVerifyEmail as AuthMustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes, CanResetPassword, AuthMustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getIsAdminAttribute()
    {
        return $this->role == UserRole::ADMIN;
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function reports()
    {
        return $this->hasMany(ReportOrder::class,'user_id','id');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function isEmailVerified()
    {
        if(!Auth::check()){
            return false;
        }

        return is_null( auth()->user()->email_verified_at )
            ? false
            : true;
    }

    public function getAvatarAttribute()
    {
        if($this->image){
            return asset('avatars/' . $this->image->name);
        }

        return "https://ui-avatars.com/api/?rounded=true&bold=true&format=svg&name=" . $this->first_name . " " .$this->last_name;
    }

    public static function getProfileImageUrl()
    {
        $user = auth()->user();
        $userImage = $user->image;

        if($userImage){
            return asset('avatars/' . $userImage->name);
        }

        return "https://ui-avatars.com/api/?rounded=true&bold=true&format=svg&name=" . $user->first_name . " " .$user->last_name;
    }

}

