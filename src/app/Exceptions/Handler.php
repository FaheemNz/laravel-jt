<?php

namespace App\Exceptions;

use App\Lib\Helper;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException as MethodNotAllowedException2;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException){
            return response()->json([
                'success' => false,
                'message' => 'Too many attempts. Please try again after 10 minutes',
                'data'    => []
            ]);    
        }
        if($exception instanceof MethodNotAllowedHttpException){
            return response()->json([
                'success' => false,
                'message' => 'The Url which you are trying to visit does not exist',
                'data'    => []
            ]);    
        }
        
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        Helper::log('### Handler - Exception ###', Helper::getExceptionInfo($exception));
        
        if($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException){
            return $this->sendError('Too many attempts. Please try again after 10 minutes');
        }
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->sendError('The Url which you are trying to visit does not exist');
        }
        if($exception instanceof ModelNotFoundException){
            Helper::log('### ModelNotFoundException ###', $exception->getMessage());
            return $this->sendError('No Query Result for the input you provided', 404);
        }
        
        return $this->sendError($exception->getMessage() . ',' . $exception->getLine() . ',' . $exception->getFile());
        return $this->sendError('Some Error occured. Please try again later! [Exception]');
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if (!(collect($request->route()->middleware())->contains('api'))) {
            return redirect()->guest('login');
        }
        //if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        //}

        //return redirect()->guest('login');
    }
    
    protected function sendError(string $message, int $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => 'Exception',
            'data'    => [$message]
        ], $status);
    }
}
