<?php

namespace App\Utilities;

use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;

class Helpers {
    
    /**
     * Error response
     *
     * @param array $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function errorResponse(
        array  $data = [],
        string $message = "Action was not successful",
        int    $statusCode = 400,
    ): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($response, $statusCode);
    }
    
    /**
     * Success response
     *
     * @param array $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function successResponse(
        array  $data = [],
        string $message = "Action was successful",
        int    $statusCode = 200,
    ): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($response, $statusCode);
    }
    
    /**
     * Generate random numbers
     * 
     * @param int $length
     * @return string
     */
    public static function randomNumbers($length = 6): string
    {
        $str = "";
        $characters = array_merge(
            range('0', '9')
        );
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = random_int(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
    
    /**
     * Convert amount
     * to kobo
     *
     * @param string|int $amount
     * @return float|int
     */
    public function convertToKobo(string|int $amount): float|int
    {
        return floatval($amount) * 100;
    }

    /**
     * Generate transaction /
     * Session Id
     * 
     * @return string
     */
    public static function generateReference(): string
    {
        return "CO_".Carbon::now()->format("ymdHms").self::randomNumbers(8);
    }

    /**
     * @param mixed $accountNumber 
     * @param mixed $noOfChars 
     * @param string $character 
     * @return string 
     */
    public static function maskPan($accountNumber, $noOfChars, $character = '*')
    {
        return substr($accountNumber, 0, 6) . str_repeat($character, strlen($accountNumber) - $noOfChars) . substr($accountNumber, -6);
    }

    /**
     * @return mixed
     */
    public static function generateStan(): mixed
    {
        $stan = sprintf("%06d", rand(0, 999999));
        return $stan;
    }

    /**
     * @return mixed 
     */
    public static function generateRrn(): mixed
    {
        $rrn = sprintf("%012d", rand(0, 999999));
        return $rrn;
    }

    /**
     * @param mixed $amount 
     * @return float 
     */
    public static function to_kobo($amount): float
    {
        return (float) $amount * 100;
    }

    /**
     * @return string 
     */
    public static function load_ip(): string
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = '';

        return explode(',', $ipaddress)[0];
    }
}


