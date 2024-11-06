<?php

namespace App\Exceptions;

use Exception;
use App\Utilities\Helpers;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    protected $message;
    protected $code;
    protected $helper;

    public function __construct($message = "Something went wrong!", $code = 400)
    {
        $this->message = $message;
        $this->code = $code;
        $this->helper = new Helpers();
    }

    /**
     * @param mixed $request 
     * @return JsonResponse
     */
    public function render($request): JsonResponse
    {
        return $this->helper->errorResponse(
            message: $this->message,
            statusCode: $this->code
        );
    }
}
