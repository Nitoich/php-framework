<?php

namespace Framework\Exceptions;

use Framework\Http\Request;

class ExceptionManager
{
    protected static array $exceptions = [];

    public static function exception(string $exception, \Closure $callback): void
    {
        static::$exceptions[$exception] = $callback;
    }

    public static function expect(\Closure $func): mixed
    {
        try
        {
            return $func();
        }
        catch (\Exception $e)
        {
            foreach (static::$exceptions as $exception => $handler)
            {
                if($e instanceof $exception)
                {
                    $handler($e);
                    die();
                }
            }

            $response = response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                ]
            ])->setStatusCode(500);

            echo $response->getData();
            die();
        }
    }
}