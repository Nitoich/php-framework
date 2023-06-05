<?php

namespace Framework\Http\Exceptions;

use Framework\Http\Interfaces\IResponse;
use JetBrains\PhpStorm\NoReturn;

class HttpResponseException extends \Exception
{
    #[NoReturn] public function __construct(IResponse $response)
    {
        \http_response_code($response->getStatusCode());
        echo $response->getData();
        die();
    }
}