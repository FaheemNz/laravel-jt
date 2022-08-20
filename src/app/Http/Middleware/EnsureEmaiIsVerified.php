<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Factory as Auth;

class EnsureEmailIsVerified
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! $request->user($guard)) {
            if($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The access token is either missing or incorrect.'
                ], 401);
            } else {
                return redirect(route('login'));
            }
        } else if
            ($request->user($guard) instanceof MustVerifyEmail &&
            ! $request->user($guard)->hasVerifiedEmail()) {

            if($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your email'
                ], 403);
            } else {
                return redirect(route('verification.notice'));
            }
        }

        $this->auth->shouldUse($guard);

        return $next($request);
    }
}