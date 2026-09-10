<?php

use App\Exceptions\AlreadyReturnedException;
use App\Exceptions\BookHasActiveBorrowingsException;
use App\Exceptions\BookUnavailableException;
use App\Exceptions\InvalidCredentialsException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->prepend(\Illuminate\Http\Middleware\HandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $message = 'Resource not found.';

            if ($e->getPrevious() instanceof ModelNotFoundException) {
                $message = match (class_basename($e->getPrevious()->getModel())) {
                    'Book' => 'Book not found.',
                    'Borrowing' => 'Borrowing not found.',
                    default => 'Resource not found.',
                };
            }

            return response()->json(['message' => $message], 404);
        });

        $exceptions->render(function (BookUnavailableException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        });

        $exceptions->render(function (AlreadyReturnedException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        });

        $exceptions->render(function (BookHasActiveBorrowingsException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        });

        $exceptions->render(function (InvalidCredentialsException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        });
    })->create();