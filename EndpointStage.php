<?php

namespace Framework;

use App\Models\User;
use Framework\DB\ORM\Abstractions\Model;
use Framework\DI\Container;
use Framework\Http\Request;
use Framework\Http\Response;

class EndpointStage extends Pipeline\PipelineStage
{
    public function __invoke(Request $request, \Closure $next): Response
    {
        $route = $request->getRoute();
        $params = $request->getParams();
        $handler = $route->getHandler();
        $container = $request->container();
        if(is_callable($handler))
        {
            $result = $container->executeClosure($handler, $params);
        } else {
            $controller = $container->get($handler[0]);
            $result = $container->executeMethod($controller, $handler[1], $params);
        }
        if($result instanceof Response) {
            return $result;
        }
        return \response($result);
    }
}