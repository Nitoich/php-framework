<?php

namespace Framework\Interfaces;

interface IConfig
{
    public static function get(string $key): mixed;
    public static function registerConfigFile(string $file_path): void;
}