<?php

namespace App\Http\Controllers;

use App\Utilities\Helpers;
use App\Exceptions\ApiException;

abstract class Controller
{
    /**
     * Instantiate Helpers class
     */
    public function helper(): Helpers
    {
        return new Helpers;
    }

    /**
     * @return mixed
     * @throws ApiException 
     */
    public static function getMerchant(): mixed
    {
        $merchant = session()->get('merchant');
        if (!$merchant) throw new ApiException("Merchant detection error");
        return (array) $merchant;
    }

    /**
     * @return mixed
     * @throws ApiException 
     */
    public static function getAgent(): mixed
    {
        $agent = session()->get('agent');
        if (!$agent) throw new ApiException("Agent detection error");
        return (array) $agent;
    }

    /**
     * @return string|array|null
     */
    public static function activeSession(): string|array|null
    {
       return request()->header('x-api-key');
    }
}
