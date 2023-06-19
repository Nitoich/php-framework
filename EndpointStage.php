<?php

namespace Framework;

use Framework\Http\Request;
use Framework\Http\Response;

class EndpointStage extends Pipeline\PipelineStage
{
    public function __invoke(Request $request, \Closure $next): Request|Response
    {
        $route = $request->getRoute();
        return $route->execute($request);
    }
}