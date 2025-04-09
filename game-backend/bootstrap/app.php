<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Definisikan alias middleware individual
        $middleware->alias([
            'role' => \App\Http\Middleware\Role::class,
            // 'auth' => \App\Http\Middleware\Authenticate::class,
            // 'csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
            // 'api.custom' => \App\Http\Middleware\ApiMiddleware::class
        ]);

        // Definisikan grup middleware web yang lengkap
        $middleware->web(append: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Tambahkan grup middleware baru
        $middleware->group('api.session', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // TIDAK menyertakan VerifyCsrfToken middleware
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 419 && $request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'CSRF token mismatch',
                    'redirect' => 'http://localhost:5173/#login'
                ], 419);
            }
        });
    })->create();
