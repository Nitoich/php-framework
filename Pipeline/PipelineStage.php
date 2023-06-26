<?php

namespace Framework\Pipeline;

use Framework\Http\Request;
use Framework\Http\Response;

abstract class PipelineStage
{
    public function __invoke(Request $request, \Closure $next): Response
    {
        return $next($request);
    }
}