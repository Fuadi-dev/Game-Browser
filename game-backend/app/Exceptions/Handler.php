<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
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
        // Tangani TokenMismatchException
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            if($request->expectsJson()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'CSRF token mismatch',
                    'redirect' => 'http://localhost:5173/#login'
                ], 419);
            }
            return redirect()->to('http://localhost:5173/#login');
        }

        // Penanganan untuk AuthenticationException (sudah ada)
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            if($request->expectsJson()){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated',
                    'redirect' => 'http://localhost:5173/#login'
                ], 401);
            }
        }

        return parent::render($request, $exception);
    }
}
