<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\Agent;
use App\Models\Merchant;
use App\Utilities\Helpers;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class MerchantMiddleware
{
    public $startTime;
    public $helper;
    public function __construct()
    {
        $this->startTime = microtime(true);
        $this->helper = new Helpers();
    }

    /**
     * @param mixed $publicKey 
     * @return bool
     * @throws Exception 
     */
    private static function isValid($publicKey): bool
    {
        $key = 'merchant-key-' . $publicKey;
        $merchant = Redis::get($key);
        if ($merchant) {
            session()->put('merchant', json_decode($merchant));
            return true;
        }
        $merchant = Merchant::where('api_key', $publicKey)
        ->first();
        if (!$merchant) throw new ApiException('Invalid Merchant Key');
        session()->put('merchant', $merchant);
        Redis::set($key, json_encode($merchant));
        return true;
    }

    /**
     * @param mixed $data 
     * @return bool
     * @throws Exception 
     */
    private static function isValidTerminalId(mixed $data): bool
    {
        if (isset($data['terminal_id'])) {
            $terminal_id = $data['terminal_id'];
            $merchant = session()->get('merchant');
            if (!$merchant) return false;
            $key = 'merchant-key-terminal-id' . $terminal_id . '-' . $merchant->id;
            $terminal = Redis::get($key);
            if ($terminal) {
                session()->put('agent', json_decode($terminal));
                return true;
            }
            $terminal = Agent::where('merchant_id', $merchant->id)
                ->where('terminal_id', $terminal_id)
                ->first();
                if (!$terminal) throw new ApiException('Terminal ID does not exists');

            session()->put('agent', json_decode($terminal));    
            Redis::set($key, $terminal);
            return true;
        }
        return false;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->startTime = microtime(true);
        $apiKey = $request->header('x-api-key');
        if (!isset($apiKey)) {
            return $this->helper->errorResponse(message: 'x-api-key is required', statusCode: 422);
        }
        if (!self::isValid($apiKey)) {
            return $this->helper->errorResponse(message: 'invalid x-api-key', statusCode: 422);
        }
        if (!self::isValidTerminalId($request->all())) {
            return $this->helper->errorResponse(message: 'Terminal id does not exists', statusCode: 422);
        }
        return $next($request);
    }

    /**
     * @param Request $request
     * @param $response
     * @return void
     */
    public function terminate(Request $request, $response): void
    {
        $data = $request->all();
        $endTime = microtime(true);
        $dataToLog  = 'Service: CASHOUT SERVICE - API' . "\n";
        $dataToLog  .= 'Time: '   . gmdate("F j, Y, g:i a") . "\n";
        $dataToLog .= 'Duration: ' . number_format($endTime - $this->startTime, 3) . "\n";
        $dataToLog .= 'IP Address: ' . $request->header('X-Real-IP') . "\n";
        $dataToLog .= 'URL: '    . $request->path() . "\n";
        $dataToLog .= 'Method: ' . $request->method() . "\n";
        $dataToLog .= 'Status: '    . $response->status() . "\n";
        $dataToLog .= 'Input: '  . json_encode($data) . "\n" ;
        $dataToLog .= 'Output: ' . $response->getContent() . "\n";
        $dataToLog .= "=============================================================================================================================================== \n";
        Log::info($dataToLog);
    }
}
