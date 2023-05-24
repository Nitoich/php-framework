<?php

namespace Framework\DB\Interfaces;

interface IDBDriverProvider
{
    public static function getDriver(string $id): ?IDBDriver;
    public static function registerDriver(string $id, IDBDriver $driver): void;
}