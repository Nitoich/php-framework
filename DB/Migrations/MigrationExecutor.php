<?php

namespace Framework\DB\Migrations;

use Framework\DB\DBDriverProvider;

class MigrationExecutor
{
    protected static array $migrations = [];

    public static function register(string $driver_id, Migration $migration): void
    {
        static::$migrations[$driver_id][] = $migration;
    }

    public static function run(string $driver_id): void
    {
        $driver = DBDriverProvider::getDriver($driver_id);
        foreach (static::$migrations[$driver_id] as $migration)
        {
            $driver->create_table($migration);
        }
    }
}