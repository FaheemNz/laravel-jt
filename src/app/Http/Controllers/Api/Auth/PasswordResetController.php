<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use Illuminate\Support\Facades\Validator;

/**
 * @group Password Reset
 * 
 */
class PasswordResetController extends BaseController
{
    /**
     * Create token password reset
     *
     * @bodyParam email string required Email of the User
     * 
     * @response
     * {
     *  "success": true,
     *  "data": [],
     *  "message": "We have e-mailed you password reset code!"
     * }
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if ($validator->fails()){
            return $this->sendError('Validation failed', $validator->errors()->all(), 400);
        }
        
        $customer = User::where('email', $request->email)->first();
        
        if (!$customer){
            return $this->sendError('Error', ['We can\'t find a user with that e-mail address'], 404);
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $customer->email],
            [
                'token' => str_random(6)
            ]
        );
        
        if ($customer && $passwordReset){
            $customer->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        }
        
        return $this->sendResponse('Error', ['We have e-mailed you password reset code!']);
    }
    
    /**
     * Find token password reset
     *
     * @bodyParam token string required Password Token
     * 
     * @response
     * {
     *  "success": true,
     *  "data": [],
     *  "message": "You password reset token object"
     * }
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        
        if (!$passwordReset){
            return $this->sendError('Error', ['This password reset token is invalid.'], 404);
        }
        
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            PasswordReset::where('token', $token)->delete();
            return $this->sendError('Error' ,['This password reset token is expired.'], 404);
        }
        
        return $this->sendResponse(['passwordReset' => $passwordReset,], 'You password reset token object');
    }
    
    /**
     * Reset password
     *
     * @bodyParam email string required Email of the User
     * @bodyParam password string required Password of the User
     * @bodyParam token string required Access token
     * 
     * @response
     * {
     *  "success": true,
     *  "data": [],
     *  "message": "Your Password have been changed successfully"
     * }
     * 
     * @response 404
     * {
     *  "success": false,
     *  "data": [],
     *  "message": "We can't find a user with that e-mail address"
     * }
     * 
     */
    public function reset(Request $request)
    {    
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string|min:8',
            'token' => 'required|string'
        ], [
            'email.email'       =>      'Email is not in correct format',
            'email.min'         =>      'Password should be atleast 8 characters long',
            'token.required'    =>      'Token is required'
        ]);
        
        if ($validator->fails()){
            return $this->sendError('Validation failed', $validator->errors()->all());
        }
        
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        
        if (!$passwordReset){
            return $this->sendError('Error', ['This password reset token is invalid.'], 404);
        }
        
        $customer = User::where('email', $passwordReset->email)->first();
        
        if (!$customer){
            return $this->sendError('Error', ['We can\'t find a user with that e-mail address.'], 404);
        }
        
        $customer->password = bcrypt($request->password);
        $customer->save();

        PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->delete();

        $customer->notify(new PasswordResetSuccess($passwordReset));
        
        return $this->sendResponse(['customer' => $customer], 'Your Password have been changed successfully');
    }
}