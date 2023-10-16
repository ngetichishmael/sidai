<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
   public function render($request, Throwable $exception)
   {
      if ($exception instanceof TokenMismatchException && !$request->expectsJson()) {

         return redirect()->route('login')->WithErrors(['message' => 'Session expired. Please log in again.']);
      }

      // Handle AuthenticationException (if needed)
      if ($exception instanceof AuthenticationException) {
//
         return redirect()->route('login')->WithErrors(['message' => 'Authentication failed. Please log in again.']);
      }
      return parent::render($request, $exception);
   }

      // Handle AuthenticationException (if needed)
//      if ($exception instanceof AuthenticationException) {
//         if ($request->expectsJson()) {
//            dd("session expired");
//            return response()->json(['error' => 'Authentication failed. Please log in again.'], 401);
//         }
//         else {
//            dd("session expired else");
//            return redirect()->route('login')->withErrors(['message' => 'Authentication failed. Please log in again.']);
//         }
//
//         return parent::render($request, $exception);
//      }}
}
