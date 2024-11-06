<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class HealthCheckController extends Controller
{
    /**
     * Health check
     * @return JsonResponse
     */
    public function health(): JsonResponse
    {
        $databaseStatus = $this->isDatabaseHealthy();
        if ($databaseStatus) {
            return response()->json(['status' => 'Application is healthy'], 200);
        }
        return response()->json(['status' => 'Application is unhealthy'], 503);
    }

    /**
     * Health check for database
     * @return bool
     */
    private function isDatabaseHealthy(): bool
    {
        try {
            DB::table('users')->first();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
