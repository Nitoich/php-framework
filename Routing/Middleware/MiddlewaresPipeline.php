<?php

namespace Framework\Routing\Middleware;

use Framework\Http\Request;
use Framework\Routing\Interfaces\IMiddleware;

class MiddlewaresPipeline
{
    protected array $middlewares = [];
    public function pipe(IMiddleware $middleware): static
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function process(Request $request, \Closure $reject): mixed
    {
        foreach ($this->middlewares as $middleware)
        {
            $middleware($request, $reject);
        }
        return $request;
    }
}