<?php

namespace Framework\Traits;

trait IsCatalog
{
    public static function register(string $key, mixed $value): void
    {
        static::$catalog[$key] = $value;
    }
    public static function get(string $key)
    {
        return static::$catalog[$key];
    }
}