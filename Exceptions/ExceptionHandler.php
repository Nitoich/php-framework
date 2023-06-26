<?php

namespace Framework\Exceptions;

use Framework\Http\Exceptions\HttpResponseException;
use Framework\Http\Exceptions\NotFoundException;
use Framework\Http\Request;
use Framework\Http\Response;

class ExceptionHandler extends \Framework\Pipeline\PipelineStage
{
    public function __invoke(Request $request, \Closure $next): Response
    {
        return ExceptionManager::expect(function () use ($request, $next) {
            return $next($request);
        });
    }
}