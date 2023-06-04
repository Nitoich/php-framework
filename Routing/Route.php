<?php

namespace Framework\Routing;

use Framework\DI\Container;
use Framework\Http\Interfaces\IRequest;
use Framework\Http\Interfaces\IResponse;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Pipeline\Pipeline;

class Route implements Interfaces\IRoute
{
    protected array $middlewares = [];

    public function __construct(
        protected string $path,
        protected string $method,
        protected array|\Closure $handler
    ) {}

    public function execute(?array $params = null): IResponse
    {
        $pipeline = new Pipeline();
        foreach ($this->middlewares as $middleware_id)
        {
            $middleware = MiddlewareProvider::get($middleware_id);
            $pipeline->pipe($middleware);
        }
        $request = $pipeline->process(Container::getInstance()->get(Request::class));

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

    public function middleware(string $middleware): static
    {
        $this->middlewares[] = $middleware;
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