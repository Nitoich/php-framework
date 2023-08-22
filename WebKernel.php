<?php

namespace Framework;

use Framework\DI\Container;
use Framework\Exceptions\ExceptionHandler;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Pipeline\PipelineStage;
use Framework\Routing\RouterMiddleware;
use Framework\Support\Middlewares\IdToModelConverter;

class WebKernel
{
    protected Container $container;

    protected array $middlewares = [
        ExceptionHandler::class,
        RouterMiddleware::class,
        IdToModelConverter::class
    ];

    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    public function getContainer(): Container
    {
        return $this->container;
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
        return function ($request) {
            return $this->processStage($request);
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