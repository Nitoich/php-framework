<?php

namespace Framework\Routing;

use Framework\DI\Container;
use Framework\Http\Exceptions\HttpResponseException;
use Framework\Http\Interfaces\IResponse;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\Middleware\MiddlewareProvider;
use Framework\Routing\Middleware\MiddlewaresPipeline;
use Framework\Routing\Traits\CanCreateRoute;

class Route implements Interfaces\IRoute
{
    use CanCreateRoute;

    protected array $params = [];

    public function __construct(
        protected string $path = '',
        protected string $method = '',
        protected array|\Closure $handler = [],
        protected ?MiddlewaresPipeline $middlewares_pipeline = null
    ) {
        $this->middlewares_pipeline = new MiddlewaresPipeline();
    }

    public function getHandler(): array|\Closure
    {
        return $this->handler;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function execute(Request $request): IResponse
    {
        /** @var Request $request */
        $this->middlewares_pipeline->process($request, function (Response $response) {
            throw new HttpResponseException($response);
        });

        if(is_callable($this->handler))
        {
            $result = (new Container())->executeClosure($this->handler, $this->params);
        } else {
            $controller = (new Container())->get($this->handler[0]);
            $result = (new Container())->executeMethod($controller, $this->handler[1], $this->params);
        }
        if(is_object($result) && method_exists($result, 'getData')) {
            return $result;
        }
        return new Response($result);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public static function prefix(string $prefix): RouteBuilder
    {
        $routeBuilder = new RouteBuilder();
        return $routeBuilder->prefix($prefix);
    }

    public static function group(\Closure $handler): RouteBuilder
    {
        $routeBuilder = new RouteBuilder();
        return $routeBuilder->group($handler);
    }

    public function middleware(string $middleware_id): static
    {
        $this->middlewares_pipeline->pipe(MiddlewareProvider::get($middleware_id));
        return $this;
    }
}