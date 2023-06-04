<?php

namespace Framework\Routing\Interfaces;

interface IMiddlewareProvider extends \Framework\Interfaces\ICatalog
{
    public static function registerMiddleware(string $key, IMiddleware $middleware): void;
    public static function getMiddleware(string $key): IMiddleware;
}