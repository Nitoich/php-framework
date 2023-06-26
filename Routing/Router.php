<?php

namespace Framework\Routing;

use Framework\DI\Container;
use Framework\Http\Interfaces\IResponse;
use Framework\Http\Response;
use Framework\Routing\Interfaces\IRoute;

class Router extends Container implements \Framework\Routing\Interfaces\IRouter
{
    private static array $routes = [
        'get' => [],
        'post' => [],
        'patch' => [],
        'delete' => [],
        'put' => []
    ];

    private static string $prefix = '';

    private static array $middlewares = [];

    public static function clearMiddlewareAndPrefix(): void
    {
        static::$middlewares = [];
        static::setPrefix('');
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function getRouteByPath(string $execute_path, string $method = 'get'): ?IRoute
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
                return $route;
            }
        }
        return null;
    }

    public static function makeRoute(string $path, string $method, array|string|\Closure $handle): IRoute
    {
        $full_path = static::$prefix . $path;
        if($full_path != '/') { $full_path = rtrim($full_path, '/'); }
        $route = new Route($full_path, $method, $handle);
        if(!empty(static::$middlewares))
        {
            foreach (static::$middlewares as $middleware)
            {
                $route->middleware($middleware);
            }
        }
        static::$routes[strtolower($method)][$full_path] = $route;
        return $route;
    }

    public static function setPrefix(string $prefix): void
    {
        static::$prefix = $prefix;
    }

    public static function addMiddleware(string $middleware_id): void
    {
        static::$middlewares[] = $middleware_id;
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