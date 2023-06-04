<?php

namespace Framework\Interfaces;

interface ICatalog
{
    public static function register(string $key, mixed $value): void;
    public static function get(string $key);
}