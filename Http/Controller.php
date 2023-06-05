<?php

namespace Framework\Http;

abstract class Controller
{
    protected array $middlewares = [];

    protected function middleware(string $middleware_key): static
    {
        $this->middlewares[] = $middleware_key;
        return $this;
    }

    protected function response(mixed $data = null): Response
    {
        return response($data);
    }
}