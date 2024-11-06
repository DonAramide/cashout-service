<?php

use Illuminate\Http\JsonResponse;


if (!function_exists('errorResponse')) {
    /**
     * Error response
     *
     * @param array $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    function errorResponse(
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
}

if (!function_exists('successResponse')) {
    /**
     * Success response
     *
     * @param array $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */

    function successResponse(
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
}


if (!function_exists('to_naira')) {
    /**
     * @param mixed $amount 
     * @return int|float 
     */
    function to_naira($amount)
    {
        return (int)$amount / 100;
    }
}

if (!function_exists('to_kobo')) {
    /**
     * @param mixed $amount 
     * @return float 
     */
    function to_kobo($amount)
    {
        return (float) $amount * 100;
    }
}

if (!function_exists('load_ip')) {
    /**
     * @return string 
     */
    function load_ip()
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

if (!function_exists('load_detail')) {
    /**
     * @param mixed $ipaddress 
     * @return array{lat: mixed, lng: mixed, ip: mixed} 
     */
    function load_detail($ipaddress)
    {
        try {
            $response = json_decode(file_get_contents("http://ip-api.com/json/{$ipaddress}"));
            if ($response->status == 'fail') {
                return [
                    'lat' => 0,
                    'lng' =>  0,
                    'ip' => $ipaddress,
                ];
            }
            return [
                'lat' => $response->lat,
                'lng' =>  $response->lon,
                'ip' => $ipaddress,
            ];
        } catch (Exception $e) {
            return [
                'lat' => 0,
                'lng' =>  0,
                'ip' => $ipaddress,
            ];
        }
    }
}
