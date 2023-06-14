<?php

namespace Framework\Routing\Traits;

use Framework\Routing\Router;

trait CanCreateRoute
{
    public static function get(string $path, array|\Closure|string $handler): \Framework\Routing\Interfaces\IRoute
    {
        return Router::makeRoute($path, 'get', $handler);
    }

    public static function post(string $path, array|\Closure|string $handler): \Framework\Routing\Interfaces\IRoute
    {
        return Router::makeRoute($path, 'post', $handler);
    }

    public static function patch(string $path, array|\Closure|string $handler): \Framework\Routing\Interfaces\IRoute
    {
        return Router::makeRoute($path, 'patch', $handler);
    }

    public static function delete(string $path, array|\Closure|string $handler): \Framework\Routing\Interfaces\IRoute
    {
        return Router::makeRoute($path, 'delete', $handler);
    }

    public static function put($path, array|\Closure|string $handler): \Framework\Routing\Interfaces\IRoute
    {
        return Router::makeRoute($path, 'put', $handler);
    }
}