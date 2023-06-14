<?php

namespace Framework\Routing\Interfaces;

use Framework\Http\Interfaces\IResponse;

interface IRoute
{
    public function __construct(string $path, string $method, array|\Closure $handler);

    public function execute(?array $params = null): IResponse;

    public function getPath(): string;

    public function middleware(string $middleware_id): static;
}