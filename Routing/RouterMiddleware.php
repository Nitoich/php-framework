<?php

namespace Framework\Routing;

use Framework\Http\Exceptions\NotFoundException;
use Framework\Http\Request;
use Framework\Http\Response;

class RouterMiddleware extends \Framework\Pipeline\PipelineStage
{
    public function __invoke(Request $request, \Closure $next): Request|Response
    {
        $route = Router::getRouteByPath($request->getPath(), $request->getMethod());
        if(!$route)
        {
//            throw new NotFoundException('Route not found! 404');
            return \response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Method not Found!'
                ]
            ])->setStatusCode(404);
        }
        $request->setRoute($route);
        return $next($request);
    }
}