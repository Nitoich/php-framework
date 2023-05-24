<?php

namespace Framework;
/*
 * 'db.driver', 'db.mysql.database', 'db.mysql.username', 'db.mysql.password' =
 * [
 *     'db' => [
 *         'driver' => '',
 *         'mysql' => [
 *             'database' => '',
 *             'username' => '',
 *             'password' => ''
 *         ]
 *     ]
 * ]
 *
 *
 */
class Config implements Interfaces\IConfig
{
    protected static array $configs = [];

    public static function get(string $key): mixed
    {
        $path = explode('.', $key);
        $result = static::$configs;
        foreach ($path as $item) { $result = @$result[$item]; }
        return $result;
    }

    public static function registerConfigFile(string $file_path): void
    {
        if(!file_exists($file_path)) { throw new \Exception('Config file not found!'); }
        $file_info = pathinfo($file_path);
        $configs = include($file_path);
        static::$configs = array_replace_recursive(static::$configs, $configs);
    }
}