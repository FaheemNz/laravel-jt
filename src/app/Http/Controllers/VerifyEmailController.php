<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * @group Email Verification
 *
 */
class VerifyEmailController extends Controller
{
    /**
     *
     * Verify Email
     *
     * @bodyParam id integer ID of the User
     *
     * @authenticated
     * @response
     * {
     *   "success": true,
     *   "message": "Email has been verified",
     *   "data": []
     * }
     *
     * @response 400
     * {
     *  "success": true,
     *  "message": "Email has already been verified",
     *  "data": []
     * }
     */
    public function verifyEmail(Request $request)
    {
        $id = $request->route('id');

        if( !$id || !is_numeric($id) ){
            return view('email-verified')->withErrors(['Invalid User ID provided']);
        }

        $user = User::find($id);

        if (!$user){
            return view('email-verified')->withErrors(['User ID not exist in system']);
        }

        if ($user->hasVerifiedEmail()) {
            return view('email-verified')->with('success', 'Email has already been verified!');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return view('email-verified')->with('success', 'Email has been verified successfully');
    }

    /**
     *
     * Resend Verification Email
     *
     * @authenticated
     * @response
     * {
     *  "success":  true,
     *  "message":  "Please check your Inbox. We have sent the verification email again",
     *  "data"   :  []
     * }
     *
     * @response 400
     * {
     *  "success": true,
     *  "message": "Email has already been verified",
     *  "data": []
     * }
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'Email has already been verified',
                'data'    => []
            ], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Please check your Inbox. We have sent the verification email again',
            'data'    => []
        ]);
    }
}
