<?php

namespace Framework\Routing;

use Framework\Http\Interfaces\IResponse;
use Framework\Http\Response;
use Framework\Routing\Interfaces\IRoute;

class Router implements \Framework\Routing\Interfaces\IRouter
{
    private static array $routes = [
        'get' => [],
        'post' => [],
        'patch' => [],
        'delete' => [],
        'put' => []
    ];
    public static function makeRoute(string $path, string $method, array|string|\Closure $handle): IRoute
    {
        $route = new Route($path, $method, $handle);
        static::$routes[strtolower($method)][$path] = $route;
        return $route;
    }

    public static function execute(string $execute_path, string $method = 'get'): IResponse
    {
        $method = strtolower($method);
        /**
         * @var string $path
         * @var IRoute $route
         */
        foreach(static::$routes[$method] as $path => $route)
        {
            $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $path);
            $pattern = str_replace('/', '\/', $pattern);

            if(preg_match("/^$pattern$/i", $execute_path, $matches))
            {
                $params = [];
                foreach ($matches as $key => $value)
                {
                    if(is_string($key)) { $params[$key] = $value; }
                }
                return $route->execute($params);
            }
        }

        return (new Response("404 Not Found"))->setStatusCode(404);
    }
}