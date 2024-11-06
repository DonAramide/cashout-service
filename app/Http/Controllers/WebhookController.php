<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class WebhookController extends Controller
{
    /**
     * Process Sarepay Virtual
     * Account Webhook
     * 
     * @param Request $request
     * @return JsonResponse
     */

    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info($payload);
        return response()->json(['status' => 'success'], 200);
    }

}
