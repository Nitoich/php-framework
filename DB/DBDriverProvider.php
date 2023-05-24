<?php

namespace Framework\DB;

use Framework\DB\Interfaces\IDBDriver;
use Framework\DB\Interfaces\IDBDriverProvider;

class DBDriverProvider implements IDBDriverProvider
{
    protected static array $drivers = [];

    public static function getDriver(string $id): ?IDBDriver
    {
        return static::$drivers[$id];
    }

    public static function registerDriver(string $id, IDBDriver $driver): void
    {
       static::$drivers[$id] = $driver;
    }
}