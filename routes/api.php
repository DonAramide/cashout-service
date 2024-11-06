<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashOutController;
use App\Http\Controllers\WebhookController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return [
        'app' => 'CASHOUT SERVICE API',
        'version' => '1.0.0',
    ];
});
Route::post('/', function () {
    return [
        'app' => 'CASHOUT SERVICE API',
        'version' => '1.0.0',
    ];
});

Route::post('webhook-test', [WebhookController::class, 'handleWebhook']);
Route::group(['prefix' => 'cashout', 'middleware' => 'merchant'], function () {
    Route::post('transact', [CashOutController::class, 'transact']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
