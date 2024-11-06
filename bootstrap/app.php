<?php

use Illuminate\Http\Response;
use Illuminate\Foundation\Application;
use App\Http\Middleware\JSONOnlyMiddleware;
use App\Http\Middleware\MerchantMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/health',
        apiPrefix: '/api/v1'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(
            append: [
                JSONOnlyMiddleware::class
            ]
        );
        $middleware->alias([
            'merchant' => MerchantMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e) {
            $response = [
                'status' => false,
                'message' => 'Resource not found',
                'data' => []
            ];
            return response()->json($response, Response::HTTP_NOT_FOUND);
        });
        $exceptions->render(function (MethodNotAllowedHttpException $e) {
            $response = [
                'status' => false,
                'message' => 'Method Not Allowed',
                'data' => []
            ];
            return response()->json($response, Response::HTTP_METHOD_NOT_ALLOWED);
        });
        $exceptions->render(function (ModelNotFoundException $e) {
            $response = [
                'status' => false,
                'message' => 'Resource not found',
                'data' => []
            ];
            return response()->json($response, Response::HTTP_NOT_FOUND);
        });
        

    })->create();
