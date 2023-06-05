<?php

namespace Framework\Routing;

use Framework\DI\Container;
use Framework\Http\Exceptions\HttpResponseException;
use Framework\Http\Interfaces\IResponse;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\Middleware\MiddlewareProvider;
use Framework\Routing\Middleware\MiddlewaresPipeline;

class Route implements Interfaces\IRoute
{
    public function __construct(
        protected string $path,
        protected string $method,
        protected array|\Closure $handler,
        protected ?MiddlewaresPipeline $middlewares_pipeline = null
    ) {
        $this->middlewares_pipeline = new MiddlewaresPipeline();
    }

    public function execute(?array $params = null): IResponse
    {
        /** @var Request $request */
        $request = Container::getInstance()->get(Request::class);
        $this->middlewares_pipeline->process($request, function (Response $response) {
            throw new HttpResponseException($response);
        });

        if(is_callable($this->handler))
        {
            return new Response(Container::getInstance()->executeClosure($this->handler, $params));
        }
        $controller = Container::getInstance()->get($this->handler[0]);
        $result = Container::getInstance()->executeMethod($controller, $this->handler[1], $params);
        return new Response($result);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function middleware(string $middleware_id): static
    {
        $this->middlewares_pipeline->pipe(MiddlewareProvider::get($middleware_id));
        return $this;
    }

    public static function get(string $path, array|\Closure|string $handler): static
    {
        return Router::makeRoute($path, 'get', $handler);
    }

    public static function post(string $path, array|\Closure|string $handler): static
    {
        return Router::makeRoute($path, 'post', $handler);
    }

    public static function patch(string $path, array|\Closure|string $handler): static
    {
        return Router::makeRoute($path, 'patch', $handler);
    }

    public static function delete(string $path, array|\Closure|string $handler): static
    {
        return Router::makeRoute($path, 'delete', $handler);
    }

    public static function put(string $path, array|\Closure|string $handler): static
    {
        return Router::makeRoute($path, 'put', $handler);
    }
}