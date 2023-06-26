<?php

namespace Framework\Routing;

use Framework\Http\Exceptions\NotFoundException;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\Interfaces\IRoute;

class RouterMiddleware extends \Framework\Pipeline\PipelineStage
{
    public function __invoke(Request $request, \Closure $next): Response
    {
        $method = strtolower($request->getMethod());
        $execute_path = $request->getPath();
        $routes = Router::getRoutes();

        foreach($routes[$method] as $path => $route)
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
                $request->setParams($params);
                $request->setRoute($route);
                return $next($request);
            }
        }
        return \response()->json([
            'error' => [
                'code' => 404,
                'message' => 'Method not Found!'
            ]
        ])->setStatusCode(404);
    }
}