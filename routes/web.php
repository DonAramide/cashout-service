<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(
        [
            'status'  => 'success',
            'message' => 'You have reached the ' . config('app.env') . ' environment of '. config('app.name')
        ],
        200
    );
});

Route::get(
    'health',
    [\App\Http\Controllers\HealthCheckController::class, 'health']
)->name('health');
