<?php

namespace Framework\Routing\Middleware;

use Framework\Routing\Interfaces;
use Framework\Routing\Interfaces\IMiddleware;
use Framework\Traits\IsCatalog;

class MiddlewareProvider implements Interfaces\IMiddlewareProvider
{
    use IsCatalog;
    private static array $catalog = [];
    public static function registerMiddleware(string $key, IMiddleware $middleware): void
    {
        static::register($key, $middleware);
    }

    public static function getMiddleware(string $key): IMiddleware
    {
        return static::get($key);
    }
}