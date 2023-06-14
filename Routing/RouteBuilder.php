<?php

namespace Framework\Routing;

use Framework\Routing\Traits\CanCreateRoute;

class RouteBuilder
{
    use CanCreateRoute;

    public function group(\Closure $handler): static
    {
        $handler();
        Router::clearMiddlewareAndPrefix();
        return $this;
    }

    public function prefix(string $prefix): static
    {
        Router::setPrefix($prefix);
        return $this;
    }

    public function middleware(string $middleware_id): static
    {
        Router::addMiddleware($middleware_id);
        return $this;
    }
}