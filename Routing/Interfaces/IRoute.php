<?php

namespace Framework\Routing\Interfaces;

use Framework\Http\Interfaces\IResponse;
use Framework\Http\Request;

interface IRoute
{
    public function __construct(string $path, string $method, array|\Closure $handler);

    public function execute(Request $request): IResponse;

    public function getPath(): string;

    public function middleware(string $middleware_id): static;
}