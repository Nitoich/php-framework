<?php

namespace Framework;

use Framework\Exceptions\ExceptionHandler;
use Framework\Exceptions\ExceptionManager;
use Framework\Http\Exceptions\NotFoundException;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Pipeline\PipelineStage;
use Framework\Routing\RouterMiddleware;

class WebKernel
{
    protected array $middlewares = [
        ExceptionHandler::class,
        RouterMiddleware::class
    ];

    public function __construct()
    {

    }

    public function pipe(PipelineStage $stage): static
    {
        $this->middlewares[] = $stage;
        return $this;
    }

    public function processStage(Request $request)
    {
        $stage = array_shift($this->middlewares);
        return $stage ? (new $stage)($request, $this->getNextClosure()) : (new EndpointStage())($request, function (){});
    }

    protected function getNextClosure(): \Closure
    {
        return function ($payload) {
            return $this->processStage($payload);
        };
    }

    public function process(Request $request): Response
    {
        $next = function (Request $request) {
            return $this->middlewares ? $this->processStage($request) : $request;
        };
        return call_user_func($next, $request);
    }
}