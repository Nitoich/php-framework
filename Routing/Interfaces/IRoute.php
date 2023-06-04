<?php

namespace Framework\Routing\Interfaces;

use Framework\Http\Interfaces\IResponse;

interface IRoute
{
    public function __construct(string $path, string $method, array|\Closure $handler);
    public function execute(?array $params = null): IResponse;
    public function getPath(): string;
    public function middleware(string $middleware): static;
    public static function get(string $path, array|\Closure|string $handler): static;
    public static function post(string $path, array|\Closure|string $handler): static;
    public static function patch(string $path, array|\Closure|string $handler): static;
    public static function delete(string $path, array|\Closure|string $handler): static;
    public static function put(string $path, array|\Closure|string $handler): static;
}