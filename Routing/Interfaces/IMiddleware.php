<?php

namespace Framework\Routing\Interfaces;

use Framework\Http\Interfaces\IRequest;
use Framework\Pipeline\Interfaces\IPipelineStage;

interface IMiddleware
{
    public function __invoke(IRequest $request, \Closure $reject): mixed;
}