<?php

namespace Framework\Routing\Interfaces;

interface IRouter
{
    public static function makeRoute(string $path, string $method, \Closure|array|string $handle);
}